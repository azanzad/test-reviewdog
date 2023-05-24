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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('planid')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('customer_type')->nullable()->comment('1=Individual Brand ,2=Parent Company');
            $table->tinyInteger('role')->default(2)->comment('1=admin,2=company');
            $table->enum('status', ['1', '2', '3'])->default('1')->comment('1=active,2=in-active,3=deleted');
            $table->string('brand_name')->nullable()->comment('company name');
            $table->string('contact_number')->nullable();
            $table->text('company_description')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_trial')->default(0)->comment('1=Give 30 day trial');
            $table->integer('trial_days')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('timezone')->nullable();
            $table->string('promo_code')->nullable();;
            $table->string('promo_code_id')->nullable();;
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
