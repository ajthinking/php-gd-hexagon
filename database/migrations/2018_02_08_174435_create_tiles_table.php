<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('background_type');
            $table->string('overlay_type');
            $table->integer('duration')->unsigned();
            $table->integer('image_x_resolution')->unsigned()->default(\App\Hexagon::DEFAULT_X_RESOLUTION);
            $table->integer('image_y_resolution')->unsigned()->default(floor(\App\Hexagon::DEFAULT_X_RESOLUTION*sqrt(3)/2));            
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
        Schema::dropIfExists('tiles');
    }
}
