<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuthRenameTheirId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth_data', function (Blueprint $table)
        {
            $table->renameColumn('theirId', 'their_id');
            
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth_data', function (Blueprint $table) {
            $table->renameColumn('their_id', 'theirId');
        });
    }

}
