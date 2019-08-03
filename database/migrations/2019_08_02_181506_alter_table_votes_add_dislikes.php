<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableVotesAddDislikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedSmallInteger('vote')
                    ->default(0)
                    ->nullable()
                    ->after('proposal_id');

            $table->unsignedSmallInteger('dislike')
                ->default(0)
                ->nullable()
                ->after('vote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('votes', 'dislike')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('dislike');
            });
        }
        if (Schema::hasColumn('votes', 'vote')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vote');
            });
        }
    }
}
