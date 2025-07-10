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
        Schema::table('services', function (Blueprint $table) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('process_hours')->nullable()->change();
                $table->string('process_minutes')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->integer('process_hours')->nullable()->change();
            $table->integer('process_minutes')->nullable()->change();
        });
    }
};
