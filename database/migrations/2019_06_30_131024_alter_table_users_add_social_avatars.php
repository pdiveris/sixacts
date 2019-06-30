<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersAddSocialAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_avatar', 400)->after('avatar')->nullable();
            $table->string('social_avatar_large', 400)->after('social_avatar')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'social_avatar_large')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('social_avatar_large');
            });
        }
        if (Schema::hasColumn('users', 'social_avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('social_avatar');
            });
        }
    }

}
