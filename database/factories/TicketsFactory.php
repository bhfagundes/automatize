<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tickets;
use Faker\Generator as Faker;

$factory->define(Tickets::class, function (Faker $faker) {

    return [
        'NUMBER' => $faker->word,
        'RES_DATE' => $faker->date('Y-m-d H:i:s'),
        'PRIORITY' => $faker->word,
        'CR_DATE' => $faker->date('Y-m-d H:i:s'),
        'UP_DATE' => $faker->date('Y-m-d H:i:s'),
        'CONF_ITEM' => $faker->word,
        'ASSIGN' => $faker->word,
        'STATUS' => $faker->word,
        'CL_CODE' => $faker->word,
        'CL_DATE' => $faker->date('Y-m-d H:i:s')
    ];
});
