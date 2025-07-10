<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/YYYY_MM_DD_create_citizen_services_table.php
return new class extends Migration {
    public function up() {
        Schema::create('citizen_services', function (Blueprint $table) {
            $table->id();
            $table->uuid('citizen_id');
            $table->uuid('service_id');
            $table->string('sequence_number', 10);
            $table->text('citizen_note')->nullable();
            $table->text('staff_node')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('qr_code', 300)->nullable();
            $table->dateTime('created_date');
            $table->dateTime('appointment_date');

            $table->dateTime('updated_date');
            $table->timestamps();

            $table->foreign('citizen_id')->references('id')->on('citizens')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('citizen_services');
    }
};
