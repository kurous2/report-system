<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'nama' => 'Kemahasiswaan',
            ],
            [
                'nama' => 'Sarana Prasarana',
            ],
            [
                'nama' => 'MIS',
            ],
            [
                'nama' => 'Tenaga Pendidik',
            ],

        ];

        foreach($categories as $key => $category){
            Category::create($category);
        }
    }
}
