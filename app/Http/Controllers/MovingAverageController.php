<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovingAverageController extends Controller
{
    public function calculateMovingAverage($data, $period)
    {
        $movingAverage = [];
        
        for ($i = 0; $i < count($data); $i++) {
            $sum = 0;
            
            for ($j = max(0, $i - $period + 1); $j <= $i; $j++) {
                $sum += $data[$j];
            }
            
            $movingAverage[] = $sum / min($period, $i + 1);
        }
        
        return $movingAverage;
    }

    public function index(Request $request)
    {
        $range = $request->input('range', 'monthly');
        $selectedMonth = $request->input('month');
        
        $data = [];
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

        $omzetData = $data->pluck('omzet')->toArray();
        $period = 3;
        $profitPrediction = $this->calculateMovingAverage($omzetData, $period);

        // Fetch Impact Secure data based on range
        $impactSecureData = [];
        if ($range == 'monthly') {
            $impactSecureData = Transaction::select(
                DB::raw('DATE_FORMAT(trx_time, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        } elseif ($range == 'weekly') {
            $impactSecureData = Transaction::select(
                DB::raw('CONCAT(YEAR(trx_time), "-", WEEK(trx_time)) as week'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('week')
            ->orderBy('week')
            ->get();
        } elseif ($range == 'daily') {
            $impactSecureData = Transaction::select(
                DB::raw('DATE(trx_time) as day'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        }

        $impactSecureCounts = $impactSecureData->pluck('impact_secure_count')->toArray();
        $impactSecurePrediction = $this->calculateMovingAverage($impactSecureCounts, $period);

        $availableMonths = Transaction::selectRaw('DATE_FORMAT(trx_time, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        $monthOptions = [];
        foreach ($availableMonths as $month) {
            $formattedMonth = Carbon::parse($month)->format('Y-m');
            $monthOptions[$formattedMonth] = Carbon::parse($formattedMonth)->format('F Y');
        }

        $labels = $data->pluck($range == 'monthly' ? 'month' : ($range == 'weekly' ? 'week' : 'day'));
        $omzet = $data->pluck('omzet');

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

        return view('movingaverage', compact(
            'labels', 'omzet', 'statistics', 'monthOptions', 
            'selectedMonth', 'profitPrediction',
            'impactSecureCounts', 'impactSecurePrediction' // Add these variables
        ));
    }
}
