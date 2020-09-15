<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned()->index();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('last_login');
            $table->boolean('is_active');
            $table->integer('role_id')->unsigned()->index();
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
