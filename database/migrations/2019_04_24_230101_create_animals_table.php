<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('animals', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('userid')->unsigned();
          $table->timestamps();
          $table->string('name');
          $table->Date('dob');
          $table->text('description')->nullable();
          $table->string('type');
          $table->string('image', 256)->nullable();
          $table->foreign('userid')->references('id')->on('users');

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
