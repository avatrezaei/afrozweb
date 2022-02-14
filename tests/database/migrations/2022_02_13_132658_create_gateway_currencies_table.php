<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('currency', 191);
            $table->string('symbol', 191);
            $table->integer('method_code')->nullable();
            $table->string('gateway_alias', 25)->nullable();
            $table->decimal('min_amount', 18, 8);
            $table->decimal('max_amount', 18, 8);
            $table->decimal('percent_charge', 5, 2)->default(0.00);
            $table->decimal('fixed_charge', 18, 8)->default(0.00000000);
            $table->decimal('rate', 18, 8)->default(0.00000000);
            $table->string('image', 191)->nullable();
            $table->text('gateway_parameter')->nullable();
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
        Schema::dropIfExists('gateway_currencies');
    }
}
