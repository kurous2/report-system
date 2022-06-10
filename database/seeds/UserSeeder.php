<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
 
    	for($i = 1; $i <= 10; $i++){
    		User::create([
                'email' => $faker->unique()->email,
    			'nrp' => $faker->unique()->numerify('##########'),
                'nama' => $faker->name,
    			'prodi' => $faker->randomElement($array = 
                    array ('D4 Teknik Informatika','D4 Teknik Elektro','D4 Mekatronika','D4 Teknologi Game',
                    'D4 Multi Media Kreatif')),
                'password' => \bcrypt('rahasia'),
            ]);
        }
    }
}
