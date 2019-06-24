<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllterAuthAllowNulls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth_data', function (Blueprint $table) {
            $table->string('nickname')->nullable()->change();
            $table->string('avatar')->nullable()->change();
            $table->string('user')->nullable()->change();
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
            $table->string('nickname')->nullable(false)->change();
            $table->string('avatar')->nullable(false)->change();
            $table->string('user')->nullable(false)->change();
        });
    }
}
