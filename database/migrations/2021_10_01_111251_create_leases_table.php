<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->boolean('residential_purpose')->default(true);
            $table->string('residential_purpose_description')->nullable();
            $table->boolean('co_ownership')->default(false);
            $table->boolean('furniture_included')->default(false);
            $table->enum('term', ['fixed', 'indeterminate']);
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->decimal('rent_amount', 10, 2);
            $table->enum('rent_recurrence', ['week', 'month']);
            $table->boolean('subsidy_program')->default(false);
            $table->date('first_payment_at');
            $table->boolean('postdated_cheques')->default(false);
            $table->date('by_law_given_on')->nullable();
            $table->boolean('land_access')->default(true);
            $table->string('land_access_description')->nullable();
            $table->boolean('animals')->default(false);
            $table->text('others')->nullable();
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
        Schema::dropIfExists('leases');
    }
}
