<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProposalsAddFulltext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = "ALTER TABLE `proposals` ADD FULLTEXT (`title`, `body`)";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $query = "ALTER TABLE `proposals` DELETE FULLTEXT (`title`, `body`);";
        DB::statement($query);
    }
}
