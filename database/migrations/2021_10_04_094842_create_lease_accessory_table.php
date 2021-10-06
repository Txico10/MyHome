<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaseAccessoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_accessory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accessory_id')->constrained();
            $table->foreignId('lease_id')->constrained();
            $table->date('assigned_at');
            $table->date('removed_at')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('lease_accessory');
    }
}
