<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => 'Manager',
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
$factory->define(App\Devis::class, function (Faker $faker) {
    return [
        'numéro' => ($faker->numerify('D##') ) ,
        'etat' => $faker->randomElement($array = array ('Ouvert', 'E.A.V', 'Validé', 'Rejetté')),
    ];
});
$factory->define(App\DevisEntree::class, function (Faker $faker) {
    return [
        'quantité' => $faker->numberBetween($min = 1, $max = 10),
        'description' => $faker->randomElement($array = array ('DJ', 'Sonorisation', 'Hôtesse', 'Graphics')),
        'prix_unitaire' => $faker->randomNumber(4)
    ];
});

$factory->define(App\Client::class, function(Faker $faker){
    return [
        'nom'  => $faker->lastName,
        'prénom' => $faker->firstName,
        'addresse' => $faker->address,
        'numéro' => $faker->phoneNumber
    ];
});

$factory->define(App\Facture::class, function (Faker $faker) {
    return [
        'numéro' => ($faker->numerify('F##') ) ,
        'etat' => $faker->randomElement($array = array ('Ouvert', 'E.A.V', 'Validé', 'Rejetté')),
    ];
});
$factory->define(App\FactureEntree::class, function (Faker $faker) {
    return [
        'quantité' => $faker->numberBetween($min = 1, $max = 10),
        'description' => $faker->randomElement($array = array ('DJ', 'Sonorisation', 'Hôtesse', 'Graphics')),
        'prix_unitaire' => $faker->randomNumber(4)
    ];
});



