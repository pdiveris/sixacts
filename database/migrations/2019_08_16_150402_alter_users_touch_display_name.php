<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTouchDisplayName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
CREATE TRIGGER add_display_name
BEFORE INSERT
ON users FOR EACH ROW
BEGIN
    IF NEW.display_name IS NULL THEN
        SET NEW.display_name=NEW.name;
    END IF;
END
EOT;
        DB::statement($query);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $stmt = "DROP TRIGGER IF EXISTS add_display_name";
        DB::statement($stmt);
    }
}
