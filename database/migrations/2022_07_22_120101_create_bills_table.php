<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_account_id');
            $table->string('month');
            $table->unsignedInteger('year');
            $table->enum('status', ['c','p','c','r']);//c-created; p-payed; c-cancelled; r-returned
            $table->date("payment_due");
            //$table->decimal('total_amount', $precision = 8, $scale = 2);
            $table->string("description")->nullable();
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
        Schema::dropIfExists('bills');
    }
}
