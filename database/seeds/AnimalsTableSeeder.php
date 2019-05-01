<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class AnimalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $this->userSeeder();
        $this->adminSeeder();
    }


    public function userSeeder()
    {
        $faker = Faker::create();
        $users = User::where('role', 0)->pluck('id')->toArray();

        //generate 10 records for the accounts table
        foreach (range(1, 100) as $index) {
            DB::table('animals')->insert([
             'userid' =>$faker->randomElement($users),
             'name' => $faker->name,
             'availability' => 'unavailable',
             'dob' => $faker->Date,
             'description' => $faker->paragraph,
             'type' => $faker->word,
             ]);
        }
    }
    public function adminSeeder()
    {
        $faker = Faker::create();
        //generate 10 records for the accounts table
        foreach (range(1, 30) as $index) {
            DB::table('animals')->insert([
             'userid' =>1,
             'name' => $faker->name,
             'availability' => 'available',
             'dob' => $faker->Date,
             'description' => $faker->paragraph,
             'type' => $faker->word,
             ]);
        }
    }
}
