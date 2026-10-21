<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Services\Payments\DonationPaymentService;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        StoreDonationRequest $request,
        PaymentGatewayManager $gateways
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
            'status_token' => (string) Str::uuid(),
        ]);

        if ($donation->payment_method === 'qris' && $gateways->isConfigured()) {
            try {
                $intent = $gateways->driver()->createPayment($donation);
                $donation->update([
                    'gateway_reference' => $intent->reference,
                    'gateway_payment_url' => $intent->paymentUrl,
                    'gateway_qr_code_url' => $intent->qrCodeUrl,
                    'payment_expires_at' => $intent->expiresAt,
                ]);
            } catch (\Throwable $exception) {
                Log::warning('Payment gateway could not create a donation payment.', [
                    'donation_code' => $donation->donation_code,
                    'exception' => $exception::class,
                ]);
            }
        }

        return redirect()
            ->route('donasi.success', ['donasi' => $donation, 'token' => $donation->status_token])
            ->with(
                'success',
                'Donasi berhasil dibuat dan menunggu pembayaran.'
            );
    }

    /**
     * Halaman sukses / instruksi pembayaran.
     */
    public function success(
        Donation $donasi,
        Request $request
    ): View {
        abort_unless(
            is_string($request->query('token'))
                && is_string($donasi->status_token)
                && hash_equals($donasi->status_token, $request->query('token')),
            404
        );

        return view('donations.success', [
            'donasi' => $donasi,
            'gatewayReady' => $donasi->gateway_reference !== null
                && ($donasi->gateway_payment_url !== null || $donasi->gateway_qr_code_url !== null),
        ]);
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
        Donation $donasi,
        DonationPaymentService $payments
    ): RedirectResponse {
        if ($donasi->payment_status !== 'pending') {
            return back()->with(
                'error',
                'Donasi sudah berstatus paid.'
            );
        }

        abort_if($donasi->payment_method === 'qris', 403, 'Pembayaran QRIS hanya dapat dikonfirmasi oleh gateway.');
        $payments->recordOfflinePayment($donasi, Auth::id());

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
        abort_if($donasi->payment_method === 'qris', 403, 'Status pembayaran QRIS hanya dapat diperbarui oleh gateway.');
        abort_if($donasi->payment_status !== 'pending', 409, 'Status donasi ini sudah diproses.');
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
