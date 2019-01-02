<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('invoice')->nullable();
            $table->integer('address_id')->nullable();
            $table->decimal('total')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('transaction_id')->nullable();

            $table->string('status')->default('pending')->notes(
                // pending = initial
                // checkout = waiting for payment
                // success == after successful payment
                // failed == after payment failed
                // cancelled == order cancelled after payment, mostly by admin
            );
            $table->string('payment_mode')->nullable();
            $table->boolean('free_wash')->default(0);

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
        Schema::dropIfExists('orders');
    }
}
