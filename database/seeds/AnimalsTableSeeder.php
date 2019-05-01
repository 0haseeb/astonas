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

        $faker = Faker::create();
        $users = User::all()->pluck('id')->toArray();

      //generate 10 records for the accounts table
       foreach (range(1,500) as $index) {
       DB::table('animals')->insert([
       'userid' =>$faker->randomElement($users),
       'name' => $faker->name,
       'dob' => $faker->Date,
       'description' => $faker->paragraph,
       'type' => $faker->word,

       ]);
       }
       }

    }
