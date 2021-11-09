<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('publikations', function (Blueprint $table) {
      $table->id();
      $table->integer('count')->nullable();
      $table->string('name');
      $table->text('authors');
      $table->text('organisation');
      $table->text('fullText');
      $table->text('refBoocks');
      $table->text('langs');
      $table->text('UDC');
      $table->text('annotation');
      $table->text('keywords');
      $table->text('FTfiles');
      $table->text('RBfiles');
      $table->string('MSC');
      $table->string('date');
      //$table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('publikations');
  }
}
