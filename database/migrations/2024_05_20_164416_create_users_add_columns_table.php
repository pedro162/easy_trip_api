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
        Schema::table('users', function (Blueprint $table) {
            
            $table->enum('user_type', ['driver', 'customer'])->nullable(false)->default('driver');
            $table->string('license_plate', 1000)->nullable();
            $table->decimal('driver_rate', 60,6)->nullable(true)->default(null)->comment('How nice is this customer');
            $table->decimal('customer_rate', 60,6)->nullable(true)->default(null)->comment('How nice is this driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropColumn(['user_type', 'license_plate', 'driver_rate', 'customer_rate']);
        });
    }
};
