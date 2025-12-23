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
        Schema::create('employee_guest_books', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guest_book_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('guest_book_id')->references('id')->on('guest_books')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_guest_books');
    }
};
