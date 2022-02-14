<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('from_id')->nullable();
            $table->integer('to_id')->nullable();
            $table->integer('level')->nullable();
            $table->decimal('commission_amount', 18, 8)->default(0.00000000);
            $table->decimal('main_amo', 18, 8)->default(0.00000000);
            $table->decimal('trx_amo', 18, 8)->default(0.00000000)->comment("Transacted Amount");
            $table->decimal('percent', 11, 2)->default(0.00);
            $table->string('title', 191)->nullable();
            $table->string('type', 50)->nullable();
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
        Schema::dropIfExists('commission_logs');
    }
}
