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
        Schema::create('exit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('purpose');
            $table->string('destination');

            $table->dateTime('exit_at');
            $table->dateTime('return_at')->nullable();

            $table->string('status', 20)->default('out');

            $table->foreignId('recorded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('returned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index('exit_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_records');
    }
};
