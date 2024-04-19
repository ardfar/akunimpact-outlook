<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = Transaction::select(
            DB::raw('DATE_FORMAT(trx_time, "%Y-%m") as month'),
            DB::raw('SUM(deal_price) as omzet')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $labels = $data->pluck('month');
        $omzet = $data->pluck('omzet');

        $statistics = null;
        if ($request->has('month')) {
            $selectedMonth = $request->input('month');
            $statistics = Transaction::select(
                DB::raw('SUM(deal_price - nett_price) as profit'),
                DB::raw('SUM(deal_price) as omzet'),
                DB::raw('AVG(deal_price - nett_price) as average_profit_per_trx')
            )
            ->whereRaw('DATE_FORMAT(trx_time, "%Y-%m") = ?', [$selectedMonth])
            ->first();
        }

        return view('index', compact('labels', 'omzet', 'statistics'));
    }
}
