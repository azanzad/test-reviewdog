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
        Schema::create('customer_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyid');
            $table->index('companyid');
            $table->foreign('companyid')->references('id')->on('users')->onDelete('cascade');
            $table->string('stripe_cardid')->nullable();
            $table->string('stripe_customerid')->nullable();
            $table->string('number');
            $table->string('last_number')->nullable();
            $table->integer('exp_month');
            $table->year('exp_year');
            $table->integer('cvc');
            $table->string('brand')->nullable();
            $table->boolean('is_primary')->default(0)->comment('1=default primary');
            $table->string('cardholder_name')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1')->comment('1=active,2=in-active,3=deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_cards');
    }
};