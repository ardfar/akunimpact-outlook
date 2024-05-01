<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Collection; // Import Collection class
use Illuminate\Support\Facades\DB;

class AnalystController extends Controller
{
    public function rankPaymentMethods($transactions)
    {
        $allMethods = [];  // Store all payment methods

        foreach ($transactions as $transaction) {
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

    public function calculateUniqueMethods($transactions)
    {
        $allMethods = [];

        foreach ($transactions as $transaction) {
            $buyerMethods = explode(",", $transaction->buyer_payments);  // Explode buyer methods
            $sellerMethods = explode(",", $transaction->seller_payments);  // Explode seller methods

            // Combine methods from both sides and remove duplicates using array_unique
            $allMethods = array_merge($allMethods, array_unique(array_merge($buyerMethods, $sellerMethods)));
        }

        return count($allMethods);  // Count unique methods
    }

    public function getHandlerStats($transactions)
    {
        $handlerValues = $transactions->pluck('handler')->unique();  // Get unique handler values

        $handlerStats = [];
        foreach ($handlerValues as $value) {
            $handlerStats[] = [
                'handler_value' => $value,
                'count' => $transactions->where('handler', $value)->count(),  // Count occurrences of each value
            ];
        }

        return $handlerStats;
    }

    public function showAnalyst()
    {
        $transactions = Transaction::select(
            'buyer_payments',
            'seller_payments',
            'handler'  // Add handler selection to query
        )
        ->get();

        $paymentRanks = $this->rankPaymentMethods($transactions);  // Call the function
        $uniqueMethodsCount = $this->calculateUniqueMethods($transactions);  // Call the function
        $handlerStats = collect($this->getHandlerStats($transactions));  // Call the new function

        return view("analyst", compact('paymentRanks', 'uniqueMethodsCount', 'handlerStats'));
    }
}