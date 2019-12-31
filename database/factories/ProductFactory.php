<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    return [
        'name'        => $faker->text(64),
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'description' => $faker->paragraph,
        'price'       => $faker->randomFloat(2, 1, 2000),
        'image_path'  => $faker->image('public/storage/uploads/images', 640, 480, null, false),
    ];
});
