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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->unsigned()->comment('Driver user id')->nullable()->default(null);
            $table->foreign('driver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('client_id')->unsigned()->comment('Customer user id');
            $table->foreign('client_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('starting_address', 1000)->nullable()->default(null);
            $table->string('starting_latitude', 1000)->nullable()->default(null);
            $table->string('starting_longitude', 1000)->nullable()->default(null);
            $table->string('end_address', 1000)->nullable()->default(null);
            $table->string('end_latitude', 1000)->nullable()->default(null);
            $table->string('end_longitude', 1000)->nullable()->default(null);
            $table->decimal('driver_rate', 60,6)->nullable(true)->default(null)->comment('How nice was this customer');
            $table->decimal('customer_rate', 60,6)->nullable(true)->default(null)->comment('How nice was this driver');
            $table->enum('trip_state', ['waiting', 'canceled', 'started', 'finished'])->nullable(false)->default('waiting')->comment('Trip state');
            $table->decimal('trip_price', 60,6)->nullable(true)->default(null)->comment('How much was this trip"');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
