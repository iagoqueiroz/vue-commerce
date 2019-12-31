<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 50)->create([
            'category_id' => function() {
                return Category::all()->random(1)->first()->id;
            }
        ]);
    }
}
