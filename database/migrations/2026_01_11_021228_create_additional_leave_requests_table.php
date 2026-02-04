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
        Schema::create('additional_leave_requests', function (Blueprint $table) {
            $table->id();

            // Pengaju
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->foreignId('work_unit_id')
                ->constrained('work_units')
                ->cascadeOnDelete();
            $table->string('position');
            $table->string('length_of_service');

            // Data cuti
            $table->text('leave_reason');
            $table->string('phone');
            $table->text('leave_address');
            $table->string('letter_number')->unique();

            // Status approval
            $table->enum('status', [
                'pending_unit_head',
                'approved_unit_head',
                'pending_head_office',
                'rejected_unit_head',
                'approved',
                'rejected'
            ])->default('pending_unit_head');

            // Approval metadata
            $table->foreignId('approved_by_unit_head')->nullable()
                ->constrained('users');

            $table->timestamp('approved_unit_head_at')->nullable();

            $table->foreignId('approved_by_head_office')->nullable()
                ->constrained('users');

            $table->timestamp('approved_head_office_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_leave_requests');
    }
};
