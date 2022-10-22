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
            $table->string("email")->nullable();
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->enum("method", ["credit","interac", "paypal", "cheque", "cash"]);//cc-credit card; interac-debit; pp-paypal
            $table->string("method_number")->nullable();
            $table->string("transaction_id", 100)->nullable();
            $table->date("payed_at");
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
