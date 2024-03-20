<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp("trx_time");
            $table->string("code");
            $table->integer("nett_price");
            $table->integer("deal_price");
            $table->string("buyer_payments");
            $table->string("seller_payments");
            $table->string("impact_secure");
            $table->string("handler");
            $table->string("payment_proof")->nullable()->default(null);
            $table->string("withdraw_proof")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
