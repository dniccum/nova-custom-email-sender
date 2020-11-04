![Nova Custom Email Sender](https://github.com/dniccum/nova-custom-email-sender/blob/master/screenshots/nova-custom-email-sender-social-image.png?raw=true)

[![Latest Version on Packagist](https://poser.pugx.org/dniccum/custom-email-sender/v/stable?format=flat-square&color=#0E7FC0)](https://packagist.org/packages/dniccum/custom-email-sender)
[![License](https://poser.pugx.org/dniccum/custom-email-sender/license?format=flat-square)](https://packagist.org/packages/dniccum/custom-email-sender)
[![Total Downloads](https://poser.pugx.org/dniccum/custom-email-sender/downloads?format=flat-square)](https://packagist.org/packages/dniccum/custom-email-sender)

This is a tool for Laravel's Nova administrator panel that allows you to send custom email messages that within your application that leverages the applications existing settings and configurations; from address, email driver, etc.

[![Screenshot](https://raw.githubusercontent.com/dniccum/nova-custom-email-sender/master/screenshots/screenshot-1.png)](https://raw.githubusercontent.com/dniccum/nova-custom-email-sender/master/screenshots/screenshot-1.png)

## Features

* Inherits your Laravel application's notification Blade layout with any and all modifications and customizations.
    * Existing email template can be published to the `views/vendor` directory
    * Allows you to provide a custom Blade template (with the appropriate variables applied)
* Provides three methods of sending messages:
    * Search the provided model by name and email
    * Via ad-hoc email input with email address validation
    * A simple toggle to send the message to all of your users
* Leverages the [Quill](https://quilljs.com/docs) WYSIWYG editor with the ability to customize the available buttons/functionality for your users
* Upload a basic HTML file to pre-populate the content for your message
* Language localization
* Preview the message before it's sent
* Various settings to adjust this tool to your installation

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

Inside `App\Providers\NovaServiceProvider`update the tools function. This will include the link on the sidebar.  
```
use Dniccum\CustomEmailSender\CustomEmailSender;

...

public function tools()
{
    return [new CustomEmailSender()];
}
```

## Upgrade from version 1.X

If you are upgrading from version 1.X **AND** you have modified the tool's default configuration, some please note the changes made to the 'from' property and update your configuration file accordingly.

### Send From settings

Below the is the "out-of-the-box" configuration for the 'from' setting:

```php
'from' => [
    'default' => config('mail.from.address'),
    'options' => [
        [
            'address' => config('mail.from.address'),
            'name' => config('mail.from.name'),
        ]
    ],
],
```

If you have a custom from address and name, add them as an associative array to the 'options' array, and indicate the default as you see fit.

### Models

You can now send emails to multiple models instead of singular model like before.

```php
'model' => [
    'classes' => [
        \App\User::class,
    ],
]
```

1. The 'class' value within the 'model' configuration is no longer used.
2. A new 'classes' value has taken it's place and accepts an array of Eloquent model classes to be passed to it.

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
            ]
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
            \App\User::class,
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
    | Email Validation Settings
    |
    */

    'validation' => [
        'max-characters' => 250000,
    ],
];
```

## Modifying "Send From" names and addresses

You have the ability to indicate which email addresses and associated names that your messages come from using the `from` property in the configuration file:

```php
'from' => [
    'default' => config('mail.from.address'),
    'options' => [
        [
            'address' => config('mail.from.address'),
            'name' => config('mail.from.name'),
        ]
    ],
],
```

You can define them via associative array using hard-coded strings, environment variables or configuration variables. Once complete, you may set the default with the 'default' property.

## Adding content

 When adding content to your message, you have two methods in which to do so. You can either:
 
* add content using the provided WYSIWYG editor
* upload an HTML file with coded content
 
### Using the file upload method

To upload HTML-based content to use within your message, begin creating a *very basic* HTML document like so, and save this file anywhere in a local environment (Desktop, Documents, Dropbox, etc):

```html
<html>
<head></head>
<body>
    <h1>Content</h1>
    <p>Your content goes here</p>
    <!-- continue adding your content -->
</body>
</html>
```

Within the `<body>` tag, add the necessary content and any other inline-CSS styles that you wish. *Please keep in mind that any CSS you add will override the template that is selected in the configuration*.

Once complete, simply toggle the HTML File toggle, click the **Select File** button to select the file, and then click the **Preview** button at the bottom to view your content.

## Localization / Translation

After the vendor files have been published, you may edit the necessary placeholders in the `resources/lang/vendor/custom-email-sender` directory.

## To Do

- [x] Email preview (thanks @Yelles for the idea!)
- [x] Localization
- [x] Add dynamic autocomplete for app's user Model
- [ ] Add support for Laravel action button components
- [x] Polish up UI
- [ ] Add additional options to further customize the Quill editor

## Credits

* [Doug Niccum](https://github.com/dniccum)
* [Andreas Bergqvist](https://github.com/andreasbergqvist)
* [Samer Halawani](https://github.com/shalawani)
* [Blake](https://github.com/StarClutch)
* [Augustusnaz](https://github.com/augustusnaz)
* [Matt Coleman](https://github.com/mattsplat)

## License

The Nova Custom Email Sender is free software licensed under the MIT license.
