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
        Schema::table('user_logs', function (Blueprint $table) {
            $table->renameColumn('zalo_id', 'user_id');
        });
    }

    public function down()
    {
        Schema::table('user_logs', function (Blueprint $table) {
            $table->renameColumn('user_id', 'zalo_id');
        });
    }
};
