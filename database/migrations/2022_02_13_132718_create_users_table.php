<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('username', 50)->unique('users_username_unique');
            $table->string('email', 90)->unique('users_email_unique');
            $table->string('mobile', 50)->nullable()->unique('mobile');
            $table->integer('ref_by')->nullable();
            $table->string('password');
            $table->string('image', 91)->nullable();
            $table->text('address')->nullable()->comment("contains full address");
            $table->decimal('deposit_wallet', 18, 8)->default(0.00000000);
            $table->decimal('interest_wallet', 18, 8)->default(0.00000000);
            $table->boolean('status')->default(1)->comment("0: banned, 1: active");
            $table->boolean('ev')->default(0)->comment("0: email unverified, 1: email verified");
            $table->boolean('sv')->default(0)->comment("0: sms unverified, 1: sms verified");
            $table->string('ver_code', 91)->nullable()->comment("stores verification code");
            $table->dateTime('ver_code_send_at')->nullable()->comment("verification send time");
            $table->boolean('ts')->default(0)->comment("0: 2fa off, 1: 2fa on");
            $table->boolean('tv')->default(1)->comment("0: 2fa unverified, 1: 2fa verified");
            $table->string('tsc')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
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
}