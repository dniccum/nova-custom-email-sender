let mix = require('laravel-mix')
let path = require('path')

require('./mix')

mix.setPublicPath('dist')
   .js('resources/js/tool.js', 'js')
   .sass('resources/sass/tool.scss', 'css')
    .disableSuccessNotifications()
    .vue({ version: 3 })
    .nova('dniccum/custom-email-sender');
