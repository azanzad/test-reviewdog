<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcription_plans', function (Blueprint $table) {
            $table->integer('interval_count')->nullable()->after('interval')->comment('bills every defined interval_count');
            $table->integer('interval')->nullable()->comment('1=day,2=week,3=month,4=year')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcription_plans', function (Blueprint $table) {
            $table->dropColumn('interval_count');
        });

    }
};