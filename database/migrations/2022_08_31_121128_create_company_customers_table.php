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
        Schema::create('company_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyid')->nullable();
            $table->index('companyid');
            $table->foreign('companyid')->references('id')->on('users')->onDelete('cascade');
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('customer_type')->nullable()->comment('1=Individual Brand ,2=Parent Company');
            $table->string('brand_name')->nullable()->comment('company name');
            $table->text('company_description')->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1')->comment('1=active,2=in-active,3=deleted');
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
        Schema::dropIfExists('company_customers');
    }
};