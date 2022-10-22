<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billables', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->foreignId('bill_id');
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->enum('oparation', ['cred','debi']);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('billables');
    }//
}
