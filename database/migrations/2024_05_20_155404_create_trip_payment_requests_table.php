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
        Schema::create('trip_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reques_description', 1000)->nullable()->default(null);
            $table->bigInteger('trip_id')->unsigned()->comment('Trip id');
            $table->foreign('trip_id')->references('id')->on('trips')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_benefic_id')->unsigned()->comment('Who is beneficiary of the bank transaction');
            $table->foreign('user_benefic_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_equest_id')->unsigned()->comment('Who requested');
            $table->foreign('user_equest_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_canceled_id')->unsigned()->nullable(true)->default(null)->comment('Who canceled the querest');
            $table->foreign('user_canceled_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_approved_id')->unsigned()->nullable(true)->default(null)->comment('Who approved the querest');
            $table->foreign('user_approved_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('trip_amout', 60,6)->nullable(true)->default(null)->comment('What was the cost of this trip');
            $table->decimal('trip_tax', 60,6)->nullable(true)->default(null)->comment('How much money should be paid to the app platform');
            $table->decimal('net_trip_amout', 60,6)->nullable(true)->default(null)->comment('How much money should be paid to the user');
            $table->enum('request_state', ['waiting', 'canceled', 'approved'])->nullable(false)->default('waiting')->comment('Trip state');
            $table->timestamp('approved_at')->useCurrent();
            $table->timestamp('canceld_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_payment_requests');
    }
};
