<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('area')->default("no area");
            $table->integer('average_price')->default(0);
            $table->string('code')->default("no code");
            $table->integer('houses_sold')->default(0);
            $table->decimal('no_of_crimes',8,2)->default(0.00);
            $table->integer('borough_flag');
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
        Schema::dropIfExists('csv_data');
    }
}
