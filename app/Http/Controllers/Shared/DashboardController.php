<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\BookLoan;
use App\Models\BusinessUnitSale;
use App\Models\Donation;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Models\Mustahik;
use App\Models\PrayerAttendance;
use App\Models\PrayerSchedule;
use App\Models\Santri;
use App\Models\User;
use App\Models\YouthMember;
use App\Models\YouthProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $role = $user->roleName();

        // Keuangan Agregat Dasar
        $totalIncome = FinancialTransaction::where('type', 'income')
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        $totalExpense = FinancialTransaction::where('type', 'expense')
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->where('status', '!=', 'rejected')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $monthlyExpense = FinancialTransaction::where('type', 'expense')
            ->where('status', '!=', 'rejected')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $totalDonation = Donation::where('payment_status', 'paid')->sum('amount');

        $pendingReports = FinancialReport::whereIn('status', ['draft', 'submitted'])->count();

        $latestTransactions = FinancialTransaction::with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(5)
            ->get();

        return match ($role) {

            'ketua' => $this->ketuaDashboard(compact(
                'totalIncome',
                'totalExpense',
                'balance',
                'monthlyIncome',
                'monthlyExpense',
                'totalDonation',
                'pendingReports',
                'latestTransactions'
            )),

            'bendahara' => $this->bendaharaDashboard(compact(
                'totalIncome',
                'totalExpense',
                'balance',
                'monthlyIncome',
                'monthlyExpense',
                'totalDonation',
                'pendingReports',
                'latestTransactions'
            )),

            'sekretaris' => $this->sekretarisDashboard(),

            'marbot' => $this->marbotDashboard(),

            'jamaah' => $this->jamaahDashboard($balance),

            default => abort(403, 'Role pengguna tidak dikenali.'),
        };
    }

    private function ketuaDashboard(array $financeData): View
    {
        $crossModuleStats = [
            'totalJamaah' => Jamaah::count(),
            'totalSantri' => Santri::where('status', 'active')->count(),
            'totalYouthMembers' => YouthMember::where('status', 'aktif')->count(),
            'totalYouthPrograms' => YouthProgram::count(),
            'totalAssets' => Asset::count(),
            'needsRepairAssets' => Asset::whereIn('condition', ['perlu_perbaikan', 'rusak'])->count(),
            'totalBooks' => LibraryBook::sum('total_copies'),
            'activeBookLoans' => BookLoan::where('status', 'borrowed')->count(),
            'activeAnnouncements' => Announcement::published()->count(),
            'recentLogs' => ActivityLog::with('user')->latest('id')->take(6)->get(),
        ];

        return view('dashboard.ketua', array_merge($financeData, $crossModuleStats));
    }

    private function bendaharaDashboard(array $financeData): View
    {
        $additional = [
            'totalMustahik' => Mustahik::count(),
            'totalZakatDisbursed' => Mustahik::where('status', 'distributed')->sum('amount'),
            'totalBusinessSalesMonth' => BusinessUnitSale::whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->sum('amount'),
            'pendingDonations' => Donation::where('payment_status', 'pending')->count(),
        ];

        return view('dashboard.bendahara', array_merge($financeData, $additional));
    }

    private function sekretarisDashboard(): View
    {
        $data = [
            'totalJamaah' => Jamaah::count(),
            'totalSantri' => Santri::where('status', 'active')->count(),
            'totalYouth' => YouthMember::where('status', 'aktif')->count(),
            'totalBooks' => LibraryBook::count(),
            'activeLoans' => BookLoan::where('status', 'borrowed')->count(),
            'upcomingActivities' => Activity::whereDate('activity_date', '>=', today())
                ->orderBy('activity_date')
                ->take(5)
                ->get(),
            'recentAnnouncements' => Announcement::latest('id')->take(5)->get(),
        ];

        return view('dashboard.sekretaris', $data);
    }

    private function marbotDashboard(): View
    {
        $today = Carbon::today();
        $data = [
            'todaySchedules' => PrayerSchedule::whereDate('schedule_date', $today)->get(),
            'todayAttendances' => PrayerAttendance::whereDate('attendance_date', $today)->count(),
            'damagedAssets' => Asset::whereIn('condition', ['perlu_perbaikan', 'rusak'])->take(5)->get(),
            'recentMaintenances' => AssetMaintenance::with('asset')->latest('scheduled_date')->take(5)->get(),
        ];

        return view('dashboard.marbot', $data);
    }

    private function jamaahDashboard(float $balance): View
    {
        $today = Carbon::today();
        $data = [
            'balance' => $balance,
            'todaySchedules' => PrayerSchedule::whereDate('schedule_date', $today)->get(),
            'upcomingActivities' => Activity::whereDate('activity_date', '>=', today())
                ->orderBy('activity_date')
                ->take(4)
                ->get(),
            'latestAnnouncements' => Announcement::published()
                ->latest('published_at')
                ->take(4)
                ->get(),
        ];

        return view('dashboard.jamaah', $data);
    }
}
