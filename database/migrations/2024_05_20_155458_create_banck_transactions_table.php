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
        Schema::create('banck_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transacion_description', 1000)->nullable(true)->default(null);
            $table->bigInteger('trip_pay_req_id')->unsigned()->comment('The Tryp payment request ID');
            $table->foreign('trip_pay_req_id')->references('id')->on('trips')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('bank_account_id')->unsigned();
            $table->foreign('bank_account_id')->references('id')->on('trips')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('transaction_type', ['debit', 'credit'])->nullable(false)->default('credit');
            $table->decimal('bank_account_balance', 60,6)->nullable(true)->default(null);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banck_transactions');
    }
};
