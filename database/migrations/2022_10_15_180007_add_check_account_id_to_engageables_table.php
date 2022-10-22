<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckAccountIdToEngageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engageables', function (Blueprint $table) {
            $table->foreignId('check_account_id')->after('team_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engageables', function (Blueprint $table) {
            $table->dropColumn('check_account_id');
        });
    }
}
