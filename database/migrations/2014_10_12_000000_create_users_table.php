<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('gender', 6)->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('email_token')->nullable();
            $table->tinyInteger('verified')->default(0);
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('slug');
            $table->string('occupation', '100')->nullable();
            $table->rememberToken();
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
