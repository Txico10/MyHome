<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->enum('salary_term', ['hourly', 'monthly', 'annual'])->nullable();
            $table->decimal('salary_amount', 9, 3)->nullable();
            $table->enum('availability', ['full-time', 'partial-time'])->nullable();
            $table->time('min_week_time')->nullable();
            $table->time('max_week_time')->nullable();
            $table->longText('agreement')->nullable();
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
        Schema::dropIfExists('employee_contracts');
    }
}
