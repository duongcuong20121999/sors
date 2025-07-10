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
            // Chuyển thành longText và vẫn giữ nullable()
            $table->longText('citizen_note')->nullable()->change();
        });
    }

    /**
     * Rollback thay đổi (nếu cần).
     */
    public function down()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            $table->text('citizen_note')->nullable()->change();
        });
    }
};
