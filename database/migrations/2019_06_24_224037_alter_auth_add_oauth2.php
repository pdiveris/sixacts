<?php
    
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class AlterAuthAddOauth2 extends Migration
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
                $table->string('refresh_token', 400)->after('token_secret');
                $table->string('expires_in', 400)->after('refresh_token');
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
                $table->dropColumn('refresh_token');
                $table->renameColumn('token_secret', 'tokenSecret');
            });
        }
    }
