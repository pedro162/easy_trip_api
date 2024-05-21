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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10000)->nullable(false)->comment('Book name');
            $table->string('isbn', 10000)->nullable(true)->default(null)->comment('Book isbn');
            $table->decimal('value', 60,6)->nullable(true)->default(null)->comment('Book price');

            $table->bigInteger('user_id')->unsigned()->comment('Who created the registration');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('user_update_id')->unsigned()->nullable()->default(null)->comment('Who updated the registration');  
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
