<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("bill_id");
            $table->foreignId("user_id");
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->enum("method", ["cc","interac", "pp", "cheque"]);//cc-credit card; interac-debit; pp-paypal
            $table->string("pay_id");
            $table->date("payment_at");
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
        Schema::dropIfExists('payments');
    }
}
