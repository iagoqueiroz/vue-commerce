<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    if(!is_dir(storage_path('app/public/uploads/images'))) {
        \Storage::makeDirectory('public/uploads/images');
    }

    $options=[
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    ]; 

    $image = file_get_contents("https://picsum.photos/640/480", false, stream_context_create($options));
    $image_name = md5(time() . uniqid()) . '.jpg';
    file_put_contents(storage_path('app/public/uploads/images/' . $image_name), $image);

    return [
        'name'        => $faker->text(64),
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'description' => $faker->paragraph,
        'price'       => $faker->randomFloat(2, 1, 2000),
        'image_path'  => $image_name,
    ];
});
