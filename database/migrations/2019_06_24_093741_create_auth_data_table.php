<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider', 255); // e,g, twitter, facebook, google..
            $table->string('scheme', 255); // e.g.OAuth, OAuth2 etc
            $table->mediumText('meta');  // serialized meta
            $table->string('theirId', 255);
            $table->string('token', 640);
            $table->string('tokenSecret', 640);
            $table->string('nickname', 255);
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('avatar', 640);
            $table->mediumText('user');    // serialized, poss JSON
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
        Schema::dropIfExists('auth_data');
    }
}
