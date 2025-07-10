<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            $table->dateTime('appointment_start_date')->nullable()->after('created_date');
        });
    }

    public function down()
    {
        Schema::table('citizen_services', function (Blueprint $table) {
            $table->dropColumn('appointment_start_date');
        });
    }
};
