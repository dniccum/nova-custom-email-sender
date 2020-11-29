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
    | Priority
    |--------------------------------------------------------------------------
    |
    | The priority in which the emails sent will be added to the queue driver.
    | In order to take advantage of this feature, you must specify queue
    | priority in your worker like so: php artisan queue:work --queue=high,low
    |
    */

    'priority' => 'low',

    /*
    |--------------------------------------------------------------------------
    | Mail Driver options
    |--------------------------------------------------------------------------
    |
    | The name and associated email address options from which the message will be sent.
    |
    */

    'from' => [
        'default' => config('mail.from.address'),
        'options' => [
            [
                'address' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ],
        ],
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
        'classes' => [
            \App\Models\User::class,
        ],
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
    | The Blade template used to send the email and if the mailable should parse
    | it as markdown. By default this template utilizes the existing notification
    | layout template. So any modifications that you have made to this layout
    | template (like the header, colors, etc) will be inherited by the view
    | supplied by this package.
    |
    | IMPORTANT: If you decide to supply your own Blade template, make sure that
    | it includes a {!! $content !!} tag as this is what is used to parse the
    | WYSIWYG's content
    |
    */

    'template' => [
        'markdown' => true,
        'view' => 'vendor.custom-email-sender.email'
    ],

    /*
    |--------------------------------------------------------------------------
    | Quill WYSIWYG editor configuration
    |--------------------------------------------------------------------------
    |
    | The basic buttons that come enabled out of the box. These were chosen to
    | eliminate the potential intrusion of the email layout. Other options are
    | available and documented on the Quill website: https://quilljs.com/docs
    |
    */

    'editor' => [

        'toolbar' => [
            [ 'header' => 1 ],
            [ 'header' => 2 ],
            [ 'list' => 'ordered' ],
            [ 'list' => 'bullet' ],
            'bold',
            'italic',
            'link',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | Email Validation Settings
    |
    */

    'validation' => [
        'max-characters' => 250000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nebula Sender
    |--------------------------------------------------------------------------
    |
    | Nebula Sender is a service that will allow you to save sent messages as
    | well as message drafts. For more information, visit: https://nebulasender.com
    |
    */
    'nebula_sender' => [

        /*
        |--------------------------------------------------------------------------
        | Nebula Sender Key
        |--------------------------------------------------------------------------
        |
        | If you are subscribing to Nebula Sender, provide your application key
        | below to utilize these features.
        |
        */
        'key' => env('NEBULA_SENDER_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Timezone
        |--------------------------------------------------------------------------
        |
        | The Timezone should the application use to parse it's dates
        |
        */
        'timezone' => env('NEBULA_SENDER_TIMEZONE', config('app.timezone')),

        /*
        |--------------------------------------------------------------------------
        | Timezone
        |--------------------------------------------------------------------------
        |
        | The Moment.js date formatting that will be used to display dates. Refer to the
        | Moment.js documentation for further details: https://momentjs.com/docs/#/displaying/
        |
        */
        'date_format' => env('NEBULA_SENDER_DATE_FORMAT', 'MMM D, YYYY \a\t h:m A'),
    ],

];
