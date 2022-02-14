<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('image', 191)->nullable();
            $table->decimal('min_limit', 18, 8)->nullable();
            $table->decimal('max_limit', 18, 8)->default(0.00000000);
            $table->string('delay', 191)->nullable();
            $table->decimal('fixed_charge', 18, 8)->nullable();
            $table->decimal('rate', 18, 8)->nullable();
            $table->decimal('percent_charge', 5, 2)->nullable();
            $table->string('currency', 20)->nullable();
            $table->text('user_data')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('withdraw_methods');
    }
}
