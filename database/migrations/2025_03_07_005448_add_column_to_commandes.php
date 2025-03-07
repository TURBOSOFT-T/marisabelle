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
        Schema::table('commandes', function (Blueprint $table) {
            $table->enum('payment_method', ['stripe', 'bank_transfer'])->default('bank_transfer'); // Mode de paiement
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // Statut du paiement
            $table->string('transaction_id')->nullable(); // ID de transaction Stripe (si applicable)
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            //
        });
    }
};
