<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuelcapacities', function (Blueprint $table) {
            $table->id();
            $table->string('fueltype_id'); 
            $table->double('ini_qty'); 
            $table->double('current_qty'); 
            $table->string('station_id'); 
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
        Schema::dropIfExists('fuelcapacities');
    }
};
