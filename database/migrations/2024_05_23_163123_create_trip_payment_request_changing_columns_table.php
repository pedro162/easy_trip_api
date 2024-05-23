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
        Schema::table('trip_payment_requests', function (Blueprint $table) {
           
            $table->bigInteger('user_canceled_id')->unsigned()->nullable(true)->default(null)->comment('Who canceled the querest')->change();
            $table->foreign('user_canceled_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('user_approved_id')->unsigned()->nullable(true)->default(null)->comment('Who approved the querest')->change();
            $table->foreign('user_approved_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_payment_requests', function (Blueprint $table) {
            $table->bigInteger('user_canceled_id')->unsigned()->nullable(true)->default(null)->comment('Who canceled the querest')->change();
            $table->foreign('user_canceled_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('user_approved_id')->unsigned()->nullable(true)->default(null)->comment('Who approved the querest')->change();
            $table->foreign('user_approved_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

        });
    }
};
