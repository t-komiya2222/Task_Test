<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

$factory->define(Post::class, function (Faker $faker) {

    $file = UploadedFile::fake()->image('');
    //$file->store('public'); 

    return [
        'user_id' => Auth::id(),
        'title' => $faker->name,
        'image' => $file,
        'description' => $faker->word
    ];
});
