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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Foreign key reference to users table for doctors
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            // Foreign key reference to users table for patients
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            // DateTime for the appointment
            $table->dateTime('appointment_date');
            // Enum status for appointment states
            $table->enum('status', ['pending', 'approved', 'rejected', 'postponed', 'canceled']);
            $table->timestamps();

            // Composite index to optimize query performance for doctor_id and appointment_date
            $table->index(['doctor_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
