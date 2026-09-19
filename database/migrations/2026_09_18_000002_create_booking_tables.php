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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();
            $table->foreignId('requester_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->string('purpose', 255);
            $table->string('destination', 255);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->integer('passenger_or_load_qty');
            $table->enum('status', ['menunggu_persetujuan', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])->default('menunggu_persetujuan');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('booking_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->unsignedTinyInteger('approval_level');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->string('notes', 255)->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_usage_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->integer('start_odometer');
            $table->integer('end_odometer')->nullable();
            $table->dateTime('actual_start_time');
            $table->dateTime('actual_end_time')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->date('fuel_date');
            $table->decimal('liters', 10, 2);
            $table->decimal('cost', 12, 2);
            $table->integer('odometer');
            $table->timestamps();
        });

        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('service_type', 100);
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('notes', 255)->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'terlewat'])->default('terjadwal');
            $table->timestamps();
        });

        Schema::create('application_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('activity', 100);
            $table->string('module', 50);
            $table->string('reference_table', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_logs');
        Schema::dropIfExists('service_schedules');
        Schema::dropIfExists('fuel_logs');
        Schema::dropIfExists('vehicle_usage_history');
        Schema::dropIfExists('booking_approvals');
        Schema::dropIfExists('bookings');
    }
};
