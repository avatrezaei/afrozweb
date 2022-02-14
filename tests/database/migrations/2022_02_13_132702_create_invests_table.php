<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('plan_id');
            $table->decimal('amount', 11, 2)->default(0.00);
            $table->decimal('interest', 11, 2)->default(0.00);
            $table->integer('period');
            $table->string('hours', 20);
            $table->string('time_name', 190);
            $table->integer('return_rec_time')->default(0);
            $table->timestamp('next_time')->default('current_timestamp()');
            $table->timestamp('last_time')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('capital_status')->comment("1 = YES & 0 = NO");
            $table->string('trx', 191)->nullable();
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
        Schema::dropIfExists('invests');
    }
}
