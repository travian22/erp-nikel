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
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('rental_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('contact_person', 50);
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 15)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->integer('year');
            $table->foreignId('category_id')->constrained('vehicle_categories')->onDelete('cascade');
            $table->enum('ownership_type', ['milik_sendiri', 'sewa']);
            $table->foreignId('rental_company_id')->nullable()->constrained('rental_companies')->onDelete('set null');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('status', ['tersedia', 'digunakan', 'maintenance'])->default('tersedia');
            $table->integer('capacity');
            $table->string('fuel_type', 20);
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('license_number', 30);
            $table->date('license_expiry');
            $table->string('phone', 20);
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('status', ['available', 'on_duty', 'off'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('rental_companies');
        Schema::dropIfExists('vehicle_categories');
    }
};
