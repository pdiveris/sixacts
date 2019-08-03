<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVotesRemoveLegacyUpDown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('votes', 'up')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('up');
            });
        }
        if (Schema::hasColumn('votes', 'down')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('down');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
