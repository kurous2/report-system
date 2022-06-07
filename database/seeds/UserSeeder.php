<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

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
 
    	    // insert data ke table pegawai menggunakan Faker
    		DB::table('users')->insert([
                'email' => $faker->unique()->email,
    			'nrp' => $faker->unique()->creditCardNumber,
                'nama' => $faker->name,
    			'prodi' => $faker->name,
                'password' => \bcrypt('rahasia'),
            ]);
        }
    }
}
