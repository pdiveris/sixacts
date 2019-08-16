<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewProposalsViewExtras extends Migration
{
    /**
     * Run the migrations.
     *
     * @version 0.7
     * @return void
     */
    public function up()
    {
        $query = <<<EOT
        CREATE OR REPLACE
VIEW `proposals_view` AS select
    `proposals`.`id` AS `id`,
    `proposals`.`title` AS `title`,
    `slugify`(`proposals`.`title`) AS `slug`,
    `proposals`.`body` AS `body`,
    `proposals`.`user_id` AS `user_id`,
    `proposals`.`created_at` AS `created_at`,
    `proposals`.`updated_at` AS `updated_at`,
    `proposals`.`category_id` AS `category_id`,
    `c`.`short_title` AS `category_short_title`,
    `c`.`sub_class` as `category_sub_class`,
    `c`.`class` as `category_class`,
     1.0 as `score`,
    `u`.`display_name` as `user_display_name`,
    `u`.`name` as `user_name`,
    `aggs`.`total_votes` as `aggs_total_votes`,
    `aggs`.`total_dislikes` as `aggs_total_dislikes`,
    (
    select sum(`v`.`vote`)
    from
        `votes` `v`
    where
        `v`.`proposal_id` = `proposals`.`id`) AS `num_votes`
from
    `proposals`
LEFT JOIN `categories` `c` ON (`c`.`id` = `proposals`.`category_id`)
LEFT JOIN `users` `u` ON (`u`.`id` = `proposals`.`user_id`)
LEFT JOIN `vote_aggs` `aggs` ON (`aggs`.`proposal_id` = `proposals`.`id`)
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
    }
}
