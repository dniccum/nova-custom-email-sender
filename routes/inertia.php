<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', [ \Dniccum\CustomEmailSender\Http\Controllers\InertiaController::class, 'home' ])
    ->name('nova.tools.custom-email-sender');

Route::get('/nebula-sender', [ \Dniccum\CustomEmailSender\Http\Controllers\InertiaController::class, 'nebulaSenderHome' ])
    ->name('nova.tools.custom-email-sender.nebula-sender-home');
Route::get('/nebula-sender/new', [ \Dniccum\CustomEmailSender\Http\Controllers\InertiaController::class, 'nebulaSenderNew' ])
    ->name('nova.tools.custom-email-sender.nebula-sender-new');
Route::get('/nebula-sender/drafts', [ \Dniccum\CustomEmailSender\Http\Controllers\InertiaController::class, 'nebulaSenderDrafts' ])
    ->name('nova.tools.custom-email-sender.nebula-sender-drafts');
Route::get('/nebula-sender/sent', [ \Dniccum\CustomEmailSender\Http\Controllers\InertiaController::class, 'nebulaSenderSentMessages' ])
    ->name('nova.tools.custom-email-sender.nebula-sender-sent');