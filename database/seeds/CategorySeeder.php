<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = ['Eletronics', 'Games', 'Clothes', 'Smartphone', 'Books', 'Acessories', 'Shoes', 'Illumination', 'Boxes'];

        foreach($cats as $cat) {
            factory(Category::class)->create([
                'name' => $cat
            ]);
        }
    }
}
