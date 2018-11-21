<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('token', 8);
            $table->string('name', 255);
            $table->string('duration', 50);
            $table->string('origin', 50);
            $table->string('destination', 50);
            $table->string('transport', 50);
            $table->string('tax', 3);
            $table->string('accommodation', 50);
            $table->string('breakfast', 100);
            $table->string('lunch', 100);
            $table->string('dinner', 100);
            $table->string('sight_seeing', 50);
            $table->string('guide_service', 3);
            $table->json('plans');
            $table->float('price');
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->integer('published_by')->nullable();
            $table->tinyInteger('published')->default(0);
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
        Schema::dropIfExists('tour_packages');
    }
}
