<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerCreateOnProposalDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
CREATE TRIGGER delete_orphaned_votes
AFTER DELETE
ON proposals FOR EACH ROW
BEGIN
    DELETE FROM votes
      WHERE votes.proposal_id = OLD.id;
END
EOT;
        
        DB::unprepared($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
