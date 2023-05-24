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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('companyid')->nullable()->after('uuid');
            $table->string('password')->nullable()->change();
            $table->double('plan_price')->nullable()->after('website');
            $table->double('discount_price')->nullable();
            $table->integer('customer_type')->nullable()->comment('1=Individual Brand ,2=Parent Company,3=sub customer')->change();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('companyid');
        });

    }
};
