<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('act', 191)->unique('plugins_act_unique');
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->string('image', 191)->nullable();
            $table->text('script')->nullable();
            $table->text('shortcode')->nullable()->comment("object");
            $table->text('support')->nullable()->comment("help section");
            $table->boolean('status')->default(1);
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('plugins');
    }
}
