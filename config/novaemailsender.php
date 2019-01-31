<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | The mail driver with which the message will be sent with.
    |
    */

    'provider' => config('mail.driver'),

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | The name and associated email address from which the message will be sent.
    |
    */

    'from' => [
        'address' => config('mail.from.address'),
        'name' => config('mail.from.name'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default User Model
    |--------------------------------------------------------------------------
    |
    | To use the "send all" feature, define the model class and the accompanying
    | properties to populate the message with.
    |
    */

    'model' => [
        'class' => \App\User::class,
        'email' => 'email',
        'name' => null,
        'first_name' => 'first_name',
        'last_name' => 'last_name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Template
    |--------------------------------------------------------------------------
    |
    | To use the "send all" feature, define the model class and the accompanying
    | properties to populate the message with.
    |
    */

    'template' => [
        'markdown' => true,
        'view' => resource_path('vendor/custom-email-sender/email')
    ],

];