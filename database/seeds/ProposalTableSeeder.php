<?php

use Illuminate\Database\Seeder;

class ProposalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proposals')->insert([
            'category_id' => 1,
            'user_id' => 1,
            'title' => 'Voting systems and how elections are conducted',
            'body' => 'Narrow \'first-past-the-post\' voting results (eg 51% vs 49%) '
             .'lead to deep national divisions that do not reflect real concensus. '
             .'Fundamental, high consequence democratic decision such as embarking upon a war, '
             .'countries or regions creating or leaving administrative federations, or decisions to '
             .'become a republic will only be validated if there is a minimum gap between yes or no '
             .'votes of twenty percent.',
        ]);

        DB::table('proposals')->insert([
            'category_id' => 2,
            'user_id' => 1,
            'title' => 'Economic and financial influences',
            'body' => 'There should be a ban on the funding of political parties by external '
             .'sources eg businesses, trade unions, private individuals, etc. Those wishing to fund '
             .'the democratic process can do so by contributing to a collective fund that pays for '
             .'the election process, the resources of which will be distributed to political parties '
             .'propoprtional to their level of membership.',
        ]);

        DB::table('proposals')->insert([
            'category_id' => 3,
            'user_id' => 1,
            'title' => 'False news stories and allegations must be corrected',
            'body' => 'An independent media court should be established, with the power to force '
             .'news organizations (including online social media platforms) to print full corrections '
             .'to false stories or allegations, These corrections would have twice the prominence '
             .'of the original false information.',
        ]);

        DB::table('proposals')->insert([
            'category_id' => 4,
            'user_id' => 1,
            'title' => 'Ban extra-political advisory/management roles while in office',
            'body' => 'No elected representative shall continue, while in office, to serve any '
             .'management or advisory '
             .' role to any private company, lobby group or other non-elected institution ',
        ]);
        
        DB::table('proposals')->insert([
            'category_id' => 5,
            'user_id' => 1,
            'title' => 'Citizens’ Assembly to replace House of Lords',
            'body' => 'The House of Lords should be replaced by a Citizens’ Assembly in Parliament',
        ]);

        DB::table('proposals')->insert([
            'category_id' => 6,
            'user_id' => 1,
            'title' => 'Independent court to protect welfare of future generations',
            'body' => 'No one generation has the right to vote for reckless policies that '
             .'endanger the welfare of future generations, eg those which seriously threaten '
             .'the stability of the global climate, such that they risk a level of social collapse '
             .'which would destabilize democratic processes for future centuries. An independent '
             .'ourt should be created, and granted the power to veto decisions which disregard the '
             .'welfare of future generations
            ',
        ]);

    }
}
