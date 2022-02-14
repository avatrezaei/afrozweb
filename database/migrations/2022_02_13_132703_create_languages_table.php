<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('code', 191);
            $table->string('icon', 191)->nullable();
            $table->boolean('text_align')->default(0)->comment("0: left to right text align, 1: right to left text align");
            $table->boolean('is_default')->default(0)->comment("0: not default language, 1: default language");
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
        Schema::dropIfExists('languages');
    }
}
