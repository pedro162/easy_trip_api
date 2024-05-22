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
        Schema::table('trips', function (Blueprint $table) {
            //$table->dropForeign(['driver_id']);

            // $table->dropIndex(['driver_id']);

            $table->bigInteger('driver_id')->unsigned()->nullable()->default(null)->comment('Driver user id')->change();
            $table->foreign('driver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {            
            $table->dropForeign(['driver_id']);
            $table->bigInteger('driver_id')->unsigned()->nullable()->comment('Driver user id')->change();
            $table->foreign('driver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
