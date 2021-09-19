<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_trips', function (Blueprint $table) {
            $table->id();
            $table->integer('respondent_id');
            $table->integer('transportation_mode_id');
            $table->integer('transportation_mode_others');
            $table->integer('duration_hours');
            $table->integer('duration_minutes');
            $table->integer('destination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multi_trips');
    }
}
