<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesColourToClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'colour')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn('colour');
                });
            }
            $table->string('class', 200)->after('short_title');
            $table->string('sub_class', 200)->after('class');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('colour', 120)->after('title');
        });
        if (Schema::hasColumn('categories', 'class')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('class');
            });
        }
        if (Schema::hasColumn('categories', 'sub_class')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('sub_class');
            });
        }
    }
}
