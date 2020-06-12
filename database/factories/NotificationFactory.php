<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => Uuid::uuid4()->toString(),
        'type' => 'App\Notification\ThreadWasUpdated',
        'notifiable_type' => 'App\User',
        'notifiable_id' => function() {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'data'  => ['foo' => 'bar']
    ];
});
