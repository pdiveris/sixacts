<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewAlterVoteAggs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
CREATE OR REPLACE
VIEW `vote_aggs` AS select
    `v1`.`proposal_id` AS `proposal_id`,
    (
        select sum(`v2`.`vote`)
    from
        `votes` `v2`
    where
        `v2`.`proposal_id` = `v1`.`proposal_id`) AS `total_votes`,
    (
        select sum(`v3`.`dislike`)
    from
        `votes` `v3`
    where
        `v3`.`proposal_id` = `v1`.`proposal_id`) AS `total_dislikes`
from
    `votes` `v1`
group by
    `v1`.`proposal_id`
order by
    `v1`.`proposal_id`;
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
        DB::statement("DROP VIEW IF EXISTS `vote_aggs`");
    }
}
