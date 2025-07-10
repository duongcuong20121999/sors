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
            // Sửa thành unsignedBigInteger vì bảng users sử dụng kiểu này
            $table->unsignedBigInteger('user_id')->nullable()->after('citizen_id');

            // Thêm khóa ngoại liên kết với bảng users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            // Xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
