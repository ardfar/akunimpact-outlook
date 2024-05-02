<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class DashboardController extends Controller
{

    public function index()
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

        // dd($weekly);

        $payment_rank = $this->get_payment_rank();
        $handler_stat = $this->get_handler_stat();

        $recent_trx = Transaction::latest("trx_time")->take(10)->get();

        $data = [
            "monthOptions" => $monthOptions,
            "genStat" => [
                "daily" => $daily,
                "weekly" => $weekly,
                "monthly" => $monthly,
                "rank" => [
                    "payment" => $payment_rank[0]["payment_method"],
                    "handler" => $handler_stat[0]["handler_value"],
                ],
                "best_cat" => $this->get_best_category()
            ],
            "recent_trx" => $recent_trx,
            "payment_method" => $payment_rank,
            "handler" => $handler_stat
        ];

        // dd($data["handler"]);

        return view('index', $data);
    }

    public function get_best_category()
    {
        $mostFrequentCategory = Transaction::select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->orderByDesc('count')
        ->first();
    
        $category = $mostFrequentCategory->category;

        return $category;
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

        return response()->json(["labels" => $labels, "omzet" => $omzet, "moving_average" => $ma, "prediction" => $pred]);
    }

    public function get_impact_secure($range)
    {
        $period = 3;
        $data = [];
        if ($range == 'monthly') {
            $data = Transaction::select(
                DB::raw('DATE_FORMAT(trx_time, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        } elseif ($range == 'weekly') {
            $data = Transaction::select(
                DB::raw('CONCAT(YEAR(trx_time), "-", WEEK(trx_time)) as week'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('week')
            ->orderBy('week')
            ->get();
        } elseif ($range == 'daily') {
            $data = Transaction::select(
                DB::raw('DATE(trx_time) as day'),
                DB::raw('SUM(CASE WHEN impact_secure = "Yes" THEN 1 ELSE 0 END) as impact_secure_count')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        }

        $impactSecureCounts = $data->pluck('impact_secure_count')->toArray();
        $ma = $this->get_moving_average($impactSecureCounts, $period);
        $last_pred = end($ma);
        $sec_las_pred = prev($ma);
        $pred = $last_pred > $sec_las_pred ? 'increase' : 'decrease';

        // dd($data->pluck("impact_secure_count"));

        $labels = $data->pluck($range == 'monthly' ? 'month' : ($range == 'weekly' ? 'week' : 'day'));

        return response()->json(["labels" => $labels, "impact_secure" => $impactSecureCounts, "moving_average" => $ma, "prediction" => $pred]);
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
        // dd($num1 - $num2);
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
        // dd($difference);
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
        // dd($difference);
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

        // dd($count);

        return array("count"=> $count, "diff" => $difference);
    }

    public function get_transaction_all()
    {
        $transactions = Transaction::select(
            'buyer_payments',
            'seller_payments',
            'handler'  // Add handler selection to query
        )
        ->get();
        return $transactions;
    }

    public function get_payment_rank()
    {
        $allMethods = [];  // Store all payment methods
        $data = $this->get_transaction_all();

        foreach ($data as $transaction) {
            $buyerMethods = explode(",", $transaction->buyer_payments);  // Explode buyer methods
            $sellerMethods = explode(",", $transaction->seller_payments);  // Explode seller methods

            // Combine methods from both sides
            $allMethods = array_merge($allMethods, $buyerMethods, $sellerMethods);
        }

        // Count occurrences of each method
        $methodCounts = array_count_values($allMethods);

        $paymentRanks = [];
        foreach ($methodCounts as $method => $count) {
            $paymentRanks[] = [
                'payment_method' => $method,
                'count' => $count,
            ];
        }

        usort($paymentRanks, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });  // Sort by count descending

        return $paymentRanks;
    }

    public function get_handler_stat()
    {
        $data = $this->get_transaction_all();
        $handlerValues = $data->pluck('handler')->unique();  // Get unique handler values

        $handlerStats = [];
        foreach ($handlerValues as $value) {
            $handlerStats[] = [
                'handler_value' => $value,
                'count' => $data->where('handler', $value)->count(),  // Count occurrences of each value
            ];
        }

        usort($handlerStats, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // dd($handlerStats);

        return $handlerStats;
    }


}
