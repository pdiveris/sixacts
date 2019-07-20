<?php
    
    use Illuminate\Database\Seeder;

    class NamesTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $faker = Faker\Factory::create();
            
            for ($i = 0; $i < 1000; $i++) {
                App\Name::create([
                    'name' => $faker->sentence(),
                ]);
            }
        }
    }
