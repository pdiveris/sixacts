<?php
    
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class AlterAuthAllowNullTokens extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('auth_data', function (Blueprint $table) {
                $table->string('token_secret')->nullable()->change();
                $table->string('refresh_token')->nullable()->change();
                $table->string('expires_in')->nullable()->change();
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
                $table->string('expires_in')->nullable(false)->change();
                $table->string('refresh_token')->nullable(false)->change();
                $table->string('token_secret')->nullable(false)->change();
            });
        }
    }
