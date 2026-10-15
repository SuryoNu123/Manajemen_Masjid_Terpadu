<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DonationController extends Controller
{
    /**
     * Form donasi publik.
     */
    public function create(): View
    {
        return view('donations.create');
    }

    /**
     * Simpan donasi dari publik.
     */
    public function store(
        StoreDonationRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $donation = Donation::create([
            'donation_code' => $this->generateDonationCode(),

            'donor_name' => $validated['donor_name'] ?? null,
            'donor_email' => $validated['donor_email'] ?? null,
            'donor_phone' => $validated['donor_phone'] ?? null,

            'type' => $validated['type'],
            'amount' => $validated['amount'],

            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('donasi.success', $donation)
            ->with(
                'success',
                'Donasi berhasil dibuat dan menunggu pembayaran.'
            );
    }

    /**
     * Halaman sukses / instruksi pembayaran.
     */
    public function success(
        Donation $donasi
    ): View {
        return view(
            'donations.success',
            compact('donasi')
        );
    }

    /**
     * Daftar donasi untuk Bendahara.
     */
    public function index(Request $request): View
    {
        $query = Donation::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        $donations = $query
            ->paginate(10)
            ->withQueryString();

        $totalPaid = Donation::where(
            'payment_status',
            'paid'
        )->sum('amount');

        $totalPending = Donation::where(
            'payment_status',
            'pending'
        )->sum('amount');

        return view(
            'donations.index',
            compact(
                'donations',
                'totalPaid',
                'totalPending'
            )
        );
    }

    /**
     * Konfirmasi pembayaran secara manual.
     *
     * Sementara digunakan selama payment gateway
     * belum terhubung.
     */
    public function markPaid(
        Donation $donasi
    ): RedirectResponse {
        if ($donasi->payment_status === 'paid') {
            return back()->with(
                'error',
                'Donasi sudah berstatus paid.'
            );
        }

        DB::transaction(function () use ($donasi) {

            $donasi->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Cari kategori keuangan
            |--------------------------------------------------------------------------
            */

            $categoryName = match ($donasi->type) {
                'infaq' => 'Infaq',
                'sedekah' => 'Sedekah',
                'zakat' => 'Zakat',
                'qurban' => 'Qurban',
            };

            $category = FinancialCategory::where(
                'name',
                $categoryName
            )
                ->where('type', 'income')
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Buat transaksi keuangan
            |--------------------------------------------------------------------------
            */

            FinancialTransaction::create([
                'category_id' => $category->id,
                'user_id' => Auth::id(),

                'transaction_code' => 'DON-'.$donasi->donation_code,

                'type' => 'income',

                'amount' => $donasi->amount,

                'transaction_date' => now()->toDateString(),

                'source' => $donasi->donor_name
                    ?: 'Donasi Online',

                'description' => 'Donasi '.
                    ucfirst($donasi->type).
                    ' - '.
                    $donasi->donation_code,

                /*
                 * Tetap draft sampai workflow laporan
                 * keuangan selesai.
                 */
                'status' => 'draft',
            ]);

        });

        return back()->with(
            'success',
            'Donasi berhasil dikonfirmasi sebagai paid dan dicatat ke transaksi keuangan.'
        );
    }

    /**
     * Tandai pembayaran gagal.
     */
    public function markFailed(
        Donation $donasi
    ): RedirectResponse {
        $donasi->update([
            'payment_status' => 'failed',
        ]);

        return back()->with(
            'success',
            'Status donasi diubah menjadi failed.'
        );
    }

    /**
     * Buat kode donasi unik.
     */
    private function generateDonationCode(): string
    {
        do {
            $code =
                'DON-'.
                now()->format('YmdHis').
                '-'.
                random_int(100, 999);

        } while (
            Donation::where(
                'donation_code',
                $code
            )->exists()
        );

        return $code;
    }
}
