<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsersAddLocalAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('local_avatar', 200)->after('display_name')->nullable();
            $table->string('local_cover', 200)->after('local_avatar')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'local_avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('local_avatar');
            });
        }
        if (Schema::hasColumn('users', 'local_cover')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('local_cover§');
            });
        }
    }
}
