<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Mendapatkan bulan dan tahun yang memiliki data
    $availableMonths = Transaction::selectRaw('DATE_FORMAT(trx_time, "%Y-%m") as month')
        ->distinct()
        ->orderBy('month', 'desc')
        ->pluck('month');

    // Menyusun pilihan bulan dalam format yang sesuai
    $monthOptions = [];
    foreach ($availableMonths as $month) {
        $formattedMonth = Carbon::parse($month)->format('Y-m');
        $monthOptions[$formattedMonth] = Carbon::parse($formattedMonth)->format('F Y');
    }

    $range = $request->input('range', 'monthly');
    $selectedMonth = $request->input('month');

    // Ambil data untuk grafik omzet sesuai dengan rentang waktu yang dipilih
    if ($range == 'monthly') {
        $data = Transaction::select(
            DB::raw('DATE_FORMAT(trx_time, "%Y-%m") as month'),
            DB::raw('SUM(deal_price) as omzet')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    } elseif ($range == 'weekly') {
        $data = Transaction::select(
            DB::raw('CONCAT(YEAR(trx_time), "-", WEEK(trx_time)) as week'),
            DB::raw('SUM(deal_price) as omzet')
        )
        ->groupBy('week')
        ->orderBy('week')
        ->get();
    } elseif ($range == 'daily') {
        $data = Transaction::select(
            DB::raw('DATE(trx_time) as day'),
            DB::raw('SUM(deal_price) as omzet')
        )
        ->groupBy('day')
        ->orderBy('day')
        ->get();
    }

    $labels = $data->pluck($range == 'monthly' ? 'month' : ($range == 'weekly' ? 'week' : 'day'));
    $omzet = $data->pluck('omzet');

    // Ambil data statistik hanya jika bulan dipilih, dan berlaku untuk rentang waktu bulanan
    $statistics = null;
    if ($selectedMonth && $range == 'monthly') {
        $statistics = Transaction::select(
            DB::raw('SUM(deal_price - nett_price) as profit'),
            DB::raw('SUM(deal_price) as omzet'),
            DB::raw('AVG(deal_price - nett_price) as average_profit_per_trx')
        )
        ->whereRaw('DATE_FORMAT(trx_time, "%Y-%m") = ?', [$selectedMonth])
        ->first();
    } elseif ($range == 'weekly') {
        // Lakukan penghitungan statistik untuk rentang waktu mingguan
        $statistics = Transaction::select(
            DB::raw('SUM(deal_price - nett_price) as profit'),
            DB::raw('SUM(deal_price) as omzet'),
            DB::raw('AVG(deal_price - nett_price) as average_profit_per_trx')
        )
        ->groupBy(DB::raw('YEAR(trx_time)'), DB::raw('WEEK(trx_time)'))
        ->orderBy(DB::raw('YEAR(trx_time)'), 'desc')
        ->orderBy(DB::raw('WEEK(trx_time)'), 'desc')
        ->first();
    } elseif ($range == 'daily') {
        $data = Transaction::select(
            DB::raw('DATE(trx_time) as day'),
            DB::raw('SUM(deal_price) as omzet')
        )
        ->groupBy('day')
        ->orderBy(DB::raw('DATE(trx_time)'), 'desc')
        ->get();
    }
    

    return view('index', compact('labels', 'omzet', 'statistics', 'monthOptions', 'selectedMonth'));
}


}