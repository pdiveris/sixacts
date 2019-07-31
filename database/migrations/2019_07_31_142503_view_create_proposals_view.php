<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewCreateProposalsView extends Migration
{
    public function up()
    {
        $query = <<<EOT
CREATE VIEW `proposals_view` AS
SELECT
  `id`,
  `title`,
   slugify(title) as slug,
  `body`,
  `user_id`,
  `created_at`,
  `updated_at`,
  `category_id`
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
