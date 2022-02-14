<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('minimum', 191);
            $table->string('maximum', 191);
            $table->string('fixed_amount', 190);
            $table->string('interest', 191);
            $table->integer('interest_status')->comment("1 = '%' / 0 ='currency'");
            $table->string('times', 191);
            $table->boolean('status')->default(0);
            $table->boolean('featured')->default(0);
            $table->integer('capital_back_status');
            $table->integer('lifetime_status');
            $table->string('repeat_time', 190);
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
        Schema::dropIfExists('plans');
    }
}
