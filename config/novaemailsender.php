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

];
