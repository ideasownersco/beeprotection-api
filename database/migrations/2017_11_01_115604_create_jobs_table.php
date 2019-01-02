<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('driver_id');
            $table->timestamp('started_working_at')->nullable()->notes('started working');
            $table->timestamp('stopped_working_at')->nullable()->notes('ended working');
            $table->timestamp('started_driving_at')->nullable()->notes('started working');
            $table->timestamp('stopped_driving_at')->nullable()->notes('ended working');
            $table->boolean('photos_approved')->default(0);
            $table->string('photo_comment')->nullable();
            $table->string('status')->default('pending')
                ->notes('
                "pending" = default,
                "working" = driver started to work on the job,
                "completed" = driver finished the job,
                ');
            $table->boolean('cancelled')->default(0);
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
        Schema::dropIfExists('jobs');
    }

}