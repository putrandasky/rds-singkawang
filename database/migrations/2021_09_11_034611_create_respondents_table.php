<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respondents', function (Blueprint $table) {
            $table->id();
            $table->integer('age_id');
            $table->integer('gender_id');
            $table->integer('job_id');
            $table->integer('income_id');
            $table->integer('avg_transportation_cost');
            $table->integer('vehicle_id');
            $table->integer('is_singkawang_domicile');
            $table->string('domicile_city');
            $table->string('domicile_province');
            $table->integer('travel_frequence');
            $table->integer('singkawang_related');
            $table->integer('city_id');
            $table->integer('travel_purpose_id');
            $table->string('travel_purpose_other');
            $table->integer('avg_trip_cost');
            $table->integer('category_id');
            $table->string('name');
            $table->string('email');
            $table->date('born_date');
            $table->string('address');
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
        Schema::dropIfExists('respondents');
    }
}
