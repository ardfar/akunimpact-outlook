<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

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

        // Get Transaction Stats period based 
        $daily = $this->get_transaction_stat_daily();
        $weekly = $this->get_transaction_stat_weekly();
        $monthly = $this->get_transaction_stat_monthly();

        $data = [
            "monthOptions" => $monthOptions,
            "genStat" => [
                "daily" => $daily,
                "weekly" => $weekly,
                "monthly" => $monthly
            ]
        ];

        return view('index', $data);
    }

    public function get_moving_average($data, $period)
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

    public function get_omzet($range)
    {
        // Ambil data untuk grafik omzet sesuai dengan rentang waktu yang dipilih
        if ($range == 'monthly') {
            $data = Transaction::select(
                DB::raw('DATE_FORMAT(trx_time, "%Y-%m") as month'),
                DB::raw('SUM(deal_price) as omzet')
            )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else if ($range == 'weekly') {
            $data = Transaction::select(
                DB::raw('CONCAT(YEAR(trx_time), "-", WEEK(trx_time)) as week'),
                DB::raw('SUM(deal_price) as omzet')
            )->groupBy('week')->orderBy('week')->get();
        } else if ($range == 'daily') {
            $data = Transaction::select(
                DB::raw('DATE(trx_time) as day'),
                DB::raw('SUM(deal_price) as omzet')
            )
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        }

        $omzet = $data->pluck('omzet');
        $period = 3;
        $ma = $this->get_moving_average($omzet->toarray(), $period);
        $last_pred = end($ma);
        $sec_las_pred = prev($ma);
        $pred = $last_pred > $sec_las_pred ? 'increase' : 'decrease';

        $labels = $data->pluck($range == 'monthly' ? 'month' : ($range == 'weekly' ? 'week' : 'day'));

        return response()->json(["labels" => $labels, "omzet" => $omzet, "moving_average" => $ma, "omzet_prediction" => $pred]);
    }

    public function get_finance_statistic($period)
    {
        $statistics = Transaction::select(
            DB::raw('SUM(deal_price - nett_price) as profit'),
            DB::raw('SUM(deal_price) as omzet'),
            DB::raw('AVG(deal_price - nett_price) as avg_profit')
        )
            ->whereRaw('DATE_FORMAT(trx_time, "%Y-%m") = ?', [$period])
            ->first();
        return $statistics;
    }

    public function get_difference_percentage($num1, $num2)
    {
        $difference = $num2 > 0 ? $num1 - $num2 : $num1;
        if ($num2 > 0) {
            $difference = ($difference / $num2) * 100;
        } else {
            $difference = 0; // Handle zero division by setting difference to 0
        }

        return $difference;
    }

    public function get_transaction_stat_weekly()
    {
        // Get current week TRX 
        $count = Transaction::whereBetween("trx_time",[
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        ])->count();

        // Get last week TRX 
        $count_old = Transaction::whereBetween("trx_time",[
            Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->subWeek()->endOfWeek(Carbon::SUNDAY)
        ])->count();

        // get Differences 
        $difference = $this->get_difference_percentage($count, $count_old);

        return array("count"=> $count, "diff" => $difference);
    }

    public function get_transaction_stat_daily()
    {
        // Get current week TRX 
        $count = Transaction::whereDate("trx_time", Carbon::now()->toDateString())->count();

        // Get last week TRX 
        $count_old = Transaction::whereDate("trx_time", Carbon::now()->toDateString())->count();

        // get Differences 
        $difference = $this->get_difference_percentage($count, $count_old);

        return array("count"=> $count, "diff" => $difference);
    }

    public function get_transaction_stat_monthly()
    {
        // Get current week TRX 
        $count = Transaction::whereYear("trx_time", Carbon::now()->year)->whereMonth("trx_time", Carbon::now()->month)->count();

        // Get last week TRX 
        $count_old = Transaction::whereYear("trx_time", Carbon::now()->subMonth()->year)->whereMonth("trx_time", Carbon::now()->subMonth()->month)->count();

        // get Differences 
        $difference = $this->get_difference_percentage($count, $count_old);

        return array("count"=> $count, "diff" => $difference);
    }
}
