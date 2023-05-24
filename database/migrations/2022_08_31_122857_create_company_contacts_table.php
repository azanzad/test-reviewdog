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
        Schema::create('company_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyid');
            $table->index('companyid');
            $table->foreign('companyid')->references('id')->on('users')->onDelete('cascade');
            $table->string('uuid')->unique();
            $table->string('contact_name');
            $table->string('contact_title')->comment('designation');
            $table->string('email');
            $table->string('contact_number')->nullable();
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
        Schema::dropIfExists('company_contacts');
    }
};