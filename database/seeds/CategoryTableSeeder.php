<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'title' => 'Voting systems and how elections are conducted',
        ]);

        DB::table('categories')->insert([
            'title' => 'Economic and financial influences',
        ]);

        DB::table('categories')->insert([
            'title' => 'Quality of political information',
        ]);

        DB::table('categories')->insert([
            'title' => 'Governance and political conduct',
        ]);

        DB::table('categories')->insert([
            'title' => 'Citizen participation',
        ]);

        DB::table('categories')->insert([
            'title' => 'Other',
        ]);

    }
}
