<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->integer('duration')->default(30)->notes('in minutes');
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('description_en')->nullable();
            $table->string('description_ar')->nullable();
            $table->decimal('price')->nullable();
            $table->string('image')->nullable();
            $table->boolean('included')->default(0);
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('services');
    }
}
