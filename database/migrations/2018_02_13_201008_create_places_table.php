<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('address1', 100);
            $table->string('address2', 100);
            $table->string('address3', 100);
            $table->string('zip_code', 10);
            $table->string('city', 30);
            $table->string('state', 30);
            $table->string('country', 30);
            $table->double('latitude');
            $table->double('longitude');
            $table->string('website', 50)->nullable();
            $table->tinyInteger('weekly_holiday')->nullable();
            $table->string('start_hour', 10)->nullable();
            $table->string('end_hour', 10)->nullable();
            $table->float('price')->nullable();
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->integer('published_by')->nullable();
            $table->tinyInteger('published')->default(0);
            $table->timestamps();
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
