<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookLoanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => [
                'nullable',
                'in:borrowed,returned,lost,overdue',
            ],
        ]);

        $status = $filters['status'] ?? '';

        $query = BookLoan::with(['book', 'jamaah']);

        if ($status === 'overdue') {
            $query->where('status', 'borrowed')
                ->whereDate('due_date', '<', today());
        } elseif ($status !== '') {
            $query->where('status', $status);
        }

        $loans = $query
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('book-loans.index', compact(
            'loans',
            'status'
        ));
    }

    public function create()
    {
        $books = LibraryBook::where('is_active', true)
            ->withCount([
                'loans as borrowed_copies' => fn ($query) => $query->where('status', 'borrowed'),
            ])
            ->orderBy('title')
            ->get()
            ->filter(fn ($book) => $book->total_copies > $book->borrowed_copies
            );

        $jamaahs = Jamaah::orderBy('id')->get();

        return view('book-loans.create', compact(
            'books',
            'jamaahs'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'library_book_id' => 'required|exists:library_books,id',
            'jamaah_id' => 'required|exists:jamaahs,id',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_at',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $book = LibraryBook::whereKey(
                $data['library_book_id']
            )->lockForUpdate()->firstOrFail();

            $borrowed = BookLoan::where(
                'library_book_id',
                $book->id
            )->where('status', 'borrowed')->count();

            if (! $book->is_active ||
                $borrowed >= $book->total_copies) {
                throw ValidationException::withMessages([
                    'library_book_id' => 'Stok buku tidak tersedia.',
                ]);
            }

            BookLoan::create([
                ...$data,
                'loan_code' => 'PJM-'.Str::upper(
                    Str::random(10)
                ),
                'status' => 'borrowed',
                'handled_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnBook(BookLoan $bookLoan)
    {
        DB::transaction(function () use ($bookLoan) {
            $bookLoan = BookLoan::whereKey($bookLoan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($bookLoan->status !== 'borrowed') {
                throw ValidationException::withMessages([
                    'loan' => 'Buku ini tidak sedang dipinjam.',
                ]);
            }

            $bookLoan->update([
                'status' => 'returned',
                'returned_at' => today()->toDateString(),
                'returned_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    public function markLost(BookLoan $bookLoan)
    {
        DB::transaction(function () use ($bookLoan): void {
            $bookLoan = BookLoan::whereKey($bookLoan->id)->lockForUpdate()->firstOrFail();
            if ($bookLoan->status !== 'borrowed') {
                throw ValidationException::withMessages(['loan' => 'Hanya buku yang sedang dipinjam yang dapat ditandai hilang.']);
            }

            $bookLoan->update(['status' => 'lost']);
        });

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Peminjaman berhasil ditandai sebagai buku hilang.');
    }

    public function report()
    {
        $totalBooks = LibraryBook::count();

        $totalCopies = LibraryBook::sum('total_copies');

        $activeLoans = BookLoan::where(
            'status',
            'borrowed'
        )->count();

        $overdueLoans = BookLoan::where('status', 'borrowed')
            ->whereDate('due_date', '<', today())
            ->count();

        $returnedLoans = BookLoan::where(
            'status',
            'returned'
        )->count();

        $lostLoans = BookLoan::where(
            'status',
            'lost'
        )->count();

        $recentLoans = BookLoan::with([
            'book',
            'jamaah',
        ])
            ->latest()
            ->limit(10)
            ->get();

        return view('book-loans.report', compact(
            'totalBooks',
            'totalCopies',
            'activeLoans',
            'overdueLoans',
            'returnedLoans',
            'lostLoans',
            'recentLoans'
        ));
    }
}
