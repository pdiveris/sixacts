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
SELECT
   `v1`.`proposal_id` AS `proposal_id`,
   (select sum(`v2`.`up`) FROM `votes` `v2` where `v2`.`proposal_id` = `v1`.`proposal_id`) AS `sum_up`,
   (select sum(`v3`.`down`) FROM `votes` `v3` where `v3`.`proposal_id` = `v1`.`proposal_id`) AS `sum_down`,
   (SELECT `sum_up` - `sum_down`) AS `total_votes`
FROM `votes` `v1` GROUP BY `v1`.`proposal_id`
ORDER BY `v1`.`proposal_id`;
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
