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
        Schema::table('services', function (Blueprint $table) {
           
            $table->text('mission')->nullable()->after('order');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
        
            $table->dropColumn('mission');
        });
    }
};
