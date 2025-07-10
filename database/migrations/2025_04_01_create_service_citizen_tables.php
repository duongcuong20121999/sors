<?php

// database/migrations/YYYY_MM_DD_create_services_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 10);
            $table->string('name', 50);
            $table->string('icon', 255);
            $table->integer('order');
            $table->boolean('is_active')->default(true);
            $table->integer('process_hours')->default(0);
            $table->integer('process_minutes')->default(0);
            $table->boolean('unlimited_duration')->default(false);
            $table->timestamps();
        });

        Schema::create('citizens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('first_name', 150);
            $table->string('avatar', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('identity_number', 12)->nullable();
            $table->dateTime('dob')->nullable();
            $table->dateTime('dop')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->dateTime('created_date')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->dateTime('last_time_login')->nullable();
            $table->string('zalo_id', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('services');
        Schema::dropIfExists('citizens');
    }
};