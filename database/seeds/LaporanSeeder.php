<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan;

class LaporanSeeder extends Seeder
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
    		Laporan::create([
                'unit' => $faker->randomElement($array = 
                    array ('Manajemen','Departemen Elektro','Departemen Informatika dan Komputer','PLCC',
                    'Perpustakaan')),
    			'uraian' => $faker->realText($maxNbChars = 100, $indexSize = 2),
                'solusi' => $faker->realText($maxNbChars = 100, $indexSize = 2),
                'gambar' => $faker->imageUrl($width = 640, $height = 480),
                'users_id' => $faker->numberBetween(1,9),
                'categories_id' => $faker->numberBetween(1,4),
            ]);
        }
    }
}
