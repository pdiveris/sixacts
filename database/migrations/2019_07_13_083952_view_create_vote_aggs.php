<?php

use Illuminate\Database\Migrations\Migration;

class ViewCreateVoteAggs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
CREATE VIEW `vote_aggs` AS
SELECT `proposal_id`,
(select sum(up) from votes v2 where v2.proposal_id=v1.proposal_id) as `sum_up`,
(select sum(down) from votes v3 where v3.proposal_id=v1.proposal_id) as `sum_down`
FROM `votes` v1
GROUP BY `proposal_id`
ORDER BY `proposal_id`
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
        //
        DB::statement("DROP VIEW `vote_aggs`");
    }
}
