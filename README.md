# Custom Email Sender for Laravel Nova

This is a tool for Laravel's Nova administrator panel that allows you to send custom email messages that within your application that leverages the applications existing settings and configurations; from address, email driver, etc.

## Features

* Inherits your Laravel application's notification Blade layout with any and all modifications and customizations.
    * Existing email template can be published to the `views/vendor` directory
    * Allows you to provide a custom Blade template (with the appropriate variables applied)
* Currently, provides two methods of sending messages:
    * A simple toggle to send the message to all of your users
    * Via ad-hoc email input with email address validation
* Leverages the [Quill](https://quilljs.com/docs) WYSIWYG editor with the ability to customize the availabe buttons/functionality for your users
* Various settings to adjust this tool to your installation.

## Installation

You can install the package via composer:

```
composer require dniccum/nova-custom-email-sender
```

You will then need to publish the package's configuration and blade view files to your applications installation:

```
php artisan vendor:publish --provider="Dniccum\CustomEmailSender\ToolServiceProvider"
```

## Configuration

The configuration items listed below can be found in the `novaemailsender.php` configuration file.

### Example

```php
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
    | The Blade template used to send the email. By default this template
    | utilizes the existing notification layout template. So any modifications
    | that you have made to this layout template (like the header, colors, etc)
    | will be inherited by the view supplied by this package.

    | IMPORTANT: If you decide to supply your own Blade template, make sure that
    | it includes a {!! $content !!} tag as this is what is used to parse the
    | WYSIWYG's content
    |
    */

    'template' => resource_path('vendor/custom-email-sender/email'),

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
```

## To Do

- [ ] Add dynamic autocomplete for app's user Model
- [ ] Add support for Laravel action button components
- [ ] Polish up UI
- [ ] Add additional options to further customize the Quill editor

## License

The Nova Custom Email Sender is free software licensed under the MIT license.
