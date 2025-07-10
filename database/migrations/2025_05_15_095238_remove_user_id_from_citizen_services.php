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
            // Xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            // Thêm lại cột user_id nếu rollback
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
