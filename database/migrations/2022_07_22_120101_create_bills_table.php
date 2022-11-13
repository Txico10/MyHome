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
            $table->foreignId('team_id')->constrained();
            $table->unsignedInteger('number')->default(0);
            $table->date('period_from');
            $table->date('period_to');
            $table->enum('status', ['created','payed','cancel']);//c-created; p-payed; c-cancelled;
            $table->date("payment_due_date");
            $table->decimal('total_amount', $precision = 8, $scale = 2)->default(0.00);
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
