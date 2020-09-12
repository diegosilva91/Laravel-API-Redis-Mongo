<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateCandidatesTable extends Migration
{

    protected $connection = 'mongodb';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id')->unsigned()->index();
            $table->string('name');
            $table->string('source');
            $table->integer('owner')->unsigned()->index();
            $table->foreignId('owner')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->index();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('cantidates');
    }
}
