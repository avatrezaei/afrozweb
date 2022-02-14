<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('method_id');
            $table->unsignedInteger('user_id');
            $table->decimal('amount', 18, 8);
            $table->string('currency', 40);
            $table->decimal('rate', 18, 8);
            $table->decimal('charge', 18, 8);
            $table->string('trx', 40)->unique('withdrawals_trx_unique');
            $table->decimal('final_amount', 18, 8)->default(0.00000000);
            $table->decimal('after_charge', 18, 8);
            $table->text('withdraw_information')->nullable();
            $table->boolean('status')->default(0)->comment("1=>success, 2=>pending, 3=>cancel,  ");
            $table->text('admin_feedback')->nullable();
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
        Schema::dropIfExists('withdrawals');
    }
}
