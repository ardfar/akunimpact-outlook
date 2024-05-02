<?php

namespace App\Http\Controllers;

use App\Models\Transaction as ModelsTransaction;
use Illuminate\Http\Request;

class Transaction extends Controller
{
    public function index()
    {
        return view("form");
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'trx_time' => "required|date",
            'code' => 'required|unique:transactions', // Must be unique in the 'transactions' table
            'category' => 'required', // Must be unique in the 'transactions' table
            'nett_price' => 'required|numeric',
            'deal_price' => 'required|numeric',
            'buyer_payments' => 'nullable|string', // Allow empty but must be numeric if provided
            'seller_payments' => 'nullable|string',
            'impact_secure' => 'nullable|string', // Allow empty but must be a string if provided
            'handler' => 'nullable|string',
        ]);
    
        // Create a new transaction record
        ModelsTransaction::create($validatedData);
    
        // Redirect back to the form with a success message
        return redirect()->back()->with('success', 'Transaction created successfully!');
    }
}
