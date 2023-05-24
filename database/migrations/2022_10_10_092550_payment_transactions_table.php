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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('plan_id')->nullable();
            $table->string('plan_interval')->nullable();
            $table->double('amount')->nullable();
            $table->enum('is_paid', ['0', '1'])->default('1')->comment('1=success,0=failed');
            $table->string('customer_id')->nullable();
            $table->string('currency', 10)->default('usd')->nullable();
            $table->string('card_token')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTime('transaction_date')->nullable();
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