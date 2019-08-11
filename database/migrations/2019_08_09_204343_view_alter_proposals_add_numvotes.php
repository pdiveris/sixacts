<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewAlterProposalsAddNumvotes extends Migration
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
VIEW `proposals_view` AS
select
    `proposals`.`id` AS `id`,
    `proposals`.`title` AS `title`,
    `slugify`(`proposals`.`title`) AS `slug`,
    `proposals`.`body` AS `body`,
    `proposals`.`user_id` AS `user_id`,
    `proposals`.`created_at` AS `created_at`,
    `proposals`.`updated_at` AS `updated_at`,
    `proposals`.`category_id` AS `category_id`,
    (select count(*) from votes v where v.proposal_id = proposals.id) as num_votes
FROM proposals
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
