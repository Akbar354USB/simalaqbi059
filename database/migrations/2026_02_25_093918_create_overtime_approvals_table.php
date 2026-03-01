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
        Schema::create('overtime_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('overtime_request_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('approved_by')
                ->constrained('employees')
                ->onDelete('cascade');

            $table->enum('approval_status', ['approved', 'rejected']);

            $table->text('note')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_approvals');
    }
};
