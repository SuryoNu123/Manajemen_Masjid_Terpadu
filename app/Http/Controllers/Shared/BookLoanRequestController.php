<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use App\Models\BookLoanRequest as LoanRequest;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookLoanRequestController extends Controller
{
    public function mine(Request $request): View
    {
        $jamaah = $request->user()->jamaah;
        $requests = $jamaah
            ? LoanRequest::with(['book', 'loan'])->where('jamaah_id', $jamaah->id)->latest()->paginate(10)
            : LoanRequest::whereKey([])->paginate(10);

        return view('book-loan-requests.mine', compact('requests', 'jamaah'));
    }

    public function store(Request $request, LibraryBook $book): RedirectResponse
    {
        abort_unless($request->user()->hasRole('jamaah'), 403);
        abort_unless($book->is_active, 404);
        $jamaah = $request->user()->jamaah;
        if (! $jamaah || $jamaah->status !== 'aktif') {
            return back()->with('error', 'Akun Jamaah belum ditautkan ke data Jamaah aktif. Hubungi pengurus masjid.');
        }

        $loanRequest = DB::transaction(function () use ($book, $jamaah): LoanRequest {
            Jamaah::whereKey($jamaah->id)->lockForUpdate()->firstOrFail();
            $book = LibraryBook::whereKey($book->id)->lockForUpdate()->firstOrFail();
            $borrowed = BookLoan::where('library_book_id', $book->id)->where('status', 'borrowed')->count();
            if (! $book->is_active || $borrowed >= $book->total_copies) {
                throw ValidationException::withMessages(['book' => 'Buku ini sedang tidak tersedia untuk diajukan.']);
            }

            $existingRequest = LoanRequest::where('jamaah_id', $jamaah->id)
                ->where('library_book_id', $book->id)
                ->where('status', 'pending')
                ->exists();

            if ($existingRequest) {
                throw ValidationException::withMessages(['book' => 'Permintaan untuk buku ini masih menunggu persetujuan.']);
            }

            return LoanRequest::create([
                'library_book_id' => $book->id,
                'jamaah_id' => $jamaah->id,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('book-loan-requests.mine')->with('success', "Permintaan buku {$book->title} berhasil dikirim.");
    }

    public function index(): View
    {
        $requests = LoanRequest::with(['book', 'jamaah', 'processor', 'loan'])
            ->latest()
            ->paginate(15);

        return view('book-loan-requests.index', compact('requests'));
    }

    public function approve(Request $request, LoanRequest $bookLoanRequest): RedirectResponse
    {
        $data = $request->validate([
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $bookLoanRequest, $data): void {
            $bookLoanRequest = LoanRequest::whereKey($bookLoanRequest->id)->lockForUpdate()->firstOrFail();
            if ($bookLoanRequest->status !== 'pending') {
                throw ValidationException::withMessages(['request' => 'Permintaan ini sudah diproses.']);
            }

            $book = LibraryBook::whereKey($bookLoanRequest->library_book_id)->lockForUpdate()->firstOrFail();
            $borrowed = BookLoan::where('library_book_id', $book->id)->where('status', 'borrowed')->count();
            if (! $book->is_active || $borrowed >= $book->total_copies) {
                throw ValidationException::withMessages(['request' => 'Stok buku tidak tersedia untuk menyetujui permintaan ini.']);
            }

            $loan = BookLoan::create([
                'loan_code' => 'PJM-'.Str::upper(Str::random(10)),
                'library_book_id' => $book->id,
                'jamaah_id' => $bookLoanRequest->jamaah_id,
                'borrowed_at' => today()->toDateString(),
                'due_date' => $data['due_date'],
                'status' => 'borrowed',
                'handled_by' => $request->user()->id,
                'notes' => $data['notes'] ?? null,
            ]);

            $bookLoanRequest->update([
                'status' => 'approved',
                'loan_id' => $loan->id,
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);
        });

        ActivityLogger::log('approve_book_loan_request', 'Menyetujui permintaan peminjaman buku.', $bookLoanRequest);

        return back()->with('success', 'Permintaan peminjaman disetujui.');
    }

    public function reject(Request $request, LoanRequest $bookLoanRequest): RedirectResponse
    {
        $data = $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        DB::transaction(function () use ($request, $bookLoanRequest, $data): void {
            $bookLoanRequest = LoanRequest::whereKey($bookLoanRequest->id)->lockForUpdate()->firstOrFail();
            if ($bookLoanRequest->status !== 'pending') {
                throw ValidationException::withMessages(['request' => 'Permintaan ini sudah diproses.']);
            }

            $bookLoanRequest->update([
                'status' => 'rejected',
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);
        });
        ActivityLogger::log('reject_book_loan_request', 'Menolak permintaan peminjaman buku.', $bookLoanRequest);

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }
}
