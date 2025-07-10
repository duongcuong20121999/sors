<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            $table->uuid('citizen_id')->nullable()->change();
            // Nếu kiểu cũ của citizen_id là uuid, nếu không phải thì thay kiểu cho đúng
        });
    }

    public function down()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            $table->uuid('citizen_id')->nullable(false)->change();
        });
    }
};
