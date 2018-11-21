<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Admin\Place\Place::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->slug,
        'address1' => $faker->streetAddress,
        'address2' => $faker->address,
        'address3' =>$faker->state,
        'zip_code' => $faker->postcode,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'admin_id' => 3,
        'published_by' => 1,
        'published' => 1
    ];
});
