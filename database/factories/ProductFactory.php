<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Product::class, function (Faker $faker) {
    return [
        "name" => $name = $faker->company,
        "slug" => Str::slug($name),
        "price" => random_int(10, 100)
    ];
});