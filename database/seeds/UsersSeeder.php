<?php

use App\Users;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();


        for ($i = 0; $i < 1; $i++) {

            $user = new Users();
            $user->userName = $faker->userName;
            $user->password = $faker->password();
            $user->firstName = $faker->firstName;
            $user->lastName = $faker->lastName;
            $user->dateOfbirth = $faker->date();
            $user->save();
            $user->groups()->attach(random_int(1,3));


        }
    }
}
