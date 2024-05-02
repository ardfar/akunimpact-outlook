<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_time',
        'code', 
        'category',
        'nett_price',
        'deal_price',
        'buyer_payments',
        'seller_payments',
        'impact_secure',
        'handler',
        'payment_proof',
        'withdraw_proof',
    ];
}
