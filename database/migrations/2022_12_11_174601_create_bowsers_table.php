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
        Schema::create('bowsers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicle_no');
            $table->double('capacity');
            $table->string('curent_location');
            $table->string('user_id');
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
        Schema::dropIfExists('bowsers');
    }
};
