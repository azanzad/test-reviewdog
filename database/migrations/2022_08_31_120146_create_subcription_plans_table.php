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
        Schema::create('subcription_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->index('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->string('uuid')->unique();
            $table->string('name');
            $table->double('amount');
            $table->tinyInteger('interval')->nullable()->comment('1=Monthly,2=Quarterly,3=Yearly');
            $table->string('currency', 10)->default('usd')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1')->comment('1=active,2=in-active,3=deleted');
            $table->integer('trial_days')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('subcription_plans');
    }
};
