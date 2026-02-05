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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->foreignId('work_shift_id')
                ->constrained()
                ->cascadeOnDelete();

            // ===== ABSEN DATANG =====
            $table->timestamp('check_in_time')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->integer('check_in_distance_meter')->nullable();
            $table->string('check_in_photo_path')->nullable();

            // ===== ABSEN PULANG =====
            $table->timestamp('check_out_time')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->integer('check_out_distance_meter')->nullable();
            $table->string('check_out_photo_path')->nullable();
            // status masuk
            $table->enum('check_in_status', ['ON_TIME', 'TERLAMBAT'])->nullable();

            // status pulang
            $table->enum('check_out_status', ['ON_TIME', 'LEBIH AWAL', 'TERLAMBAT'])->nullable();

            $table->timestamps();

            // 1 pegawai hanya 1 data per hari per shift
            $table->unique([
                'employee_id',
                'attendance_date',
                'work_shift_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendaces');
    }
};
