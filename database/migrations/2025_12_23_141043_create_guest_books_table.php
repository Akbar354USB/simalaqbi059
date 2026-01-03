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
        Schema::create('guest_books', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('number_phone');
            // foreign key ke tabel agencies
            $table->foreignId('agency_id')
                ->constrained('agencies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('objective');
            $table->time('arrival_time')->nullable(); // bisa nullable jika tidak wajib diisi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
