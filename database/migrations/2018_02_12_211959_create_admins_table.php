<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('email_token')->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('gender', 6)->nullable();
            $table->boolean('status')->default(0);
            $table->integer('role_id')->unsigned()->index();
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
        Schema::dropIfExists('admins');
    }
}
