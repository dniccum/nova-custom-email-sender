# Custom Email Sender for Laravel Nova

[![Latest Version on Packagist](https://poser.pugx.org/dniccum/custom-email-sender/v/stable?format=flat-square&color=#0E7FC0)](https://packagist.org/packages/dniccum/custom-email-sender)
[![License](https://poser.pugx.org/dniccum/custom-email-sender/license?format=flat-square)](https://packagist.org/packages/dniccum/custom-email-sender)
[![Total Downloads](https://poser.pugx.org/dniccum/custom-email-sender/downloads?format=flat-square)](https://packagist.org/packages/dniccum/custom-email-sender)

This is a tool for Laravel's Nova administrator panel that allows you to send custom email messages that within your application that leverages the applications existing settings and configurations; from address, email driver, etc.

[![Screenshot](https://raw.githubusercontent.com/dniccum/nova-custom-email-sender/master/screenshots/screenshot-1.png)](https://raw.githubusercontent.com/dniccum/nova-custom-email-sender/master/screenshots/screenshot-1.png)

## Features

* Inherits your Laravel application's notification Blade layout with any and all modifications and customizations.
    * Existing email template can be published to the `views/vendor` directory
    * Allows you to provide a custom Blade template (with the appropriate variables applied)
* Currently, provides two methods of sending messages:
    * A simple toggle to send the message to all of your users
    * Via ad-hoc email input with email address validation
* Leverages the [Quill](https://quilljs.com/docs) WYSIWYG editor with the ability to customize the available buttons/functionality for your users
* Language localization
* Preview the message before it's sent
* Various settings to adjust this tool to your installation.

## Installation

You can install the package via composer:

```
composer require dniccum/custom-email-sender
```

You will then need to publish the package's configuration and blade view files to your applications installation:

```
php artisan vendor:publish --provider="Dniccum\CustomEmailSender\ToolServiceProvider"
```

If you would only like to publish a portion of the vendor assets, you may use the following tags:

* config
* views
* lang

with the necessary artisan command like so:

```
php artisan vendor:publish --provider="Dniccum\CustomEmailSender\ToolServiceProvider" --tag=config
```

## Configuration

The configuration items listed below can be found in the `novaemailsender.php` configuration file.

### Example

```php
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
```

## Localization / Translation

After the vendor files have been published, you may edit the necessary placeholders in the `resources/lang/vendor/custom-email-sender` directory.

## To Do

- [x] Email preview (thanks @Yelles for the idea!)
- [x] Localization
- [ ] Add dynamic autocomplete for app's user Model
- [ ] Add support for Laravel action button components
- [ ] Polish up UI
- [ ] Add additional options to further customize the Quill editor

## License

The Nova Custom Email Sender is free software licensed under the MIT license.
