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
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');

            $table->time('start_time');
            $table->time('end_time');

            // jumlah hari shift berlangsung
            // contoh:
            // shift malam = 1
            // shift jumat-sabtu = 1
            // shift sabtu-senin = 2
            $table->unsignedTinyInteger('day_span')->default(0);

            // penanda shift lintas hari
            $table->boolean('is_cross_day')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
    }
};
