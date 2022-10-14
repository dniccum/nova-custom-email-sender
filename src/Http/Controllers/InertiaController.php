<?php


namespace Dniccum\CustomEmailSender\Http\Controllers;


use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Http;

class InertiaController
{
    public function home(NovaRequest $request)
    {
        return inertia('CustomEmailSender', [
            'settings' => \Dniccum\CustomEmailSender\Library\ConfigUtility::config()
        ]);
    }

    public function nebulaSenderHome(NovaRequest $request)
    {
        return inertia('NebulaSenderHome', [
            'settings' => \Dniccum\CustomEmailSender\Library\ConfigUtility::config(),
        ]);
    }

    public function nebulaSenderNew(NovaRequest $request)
    {
        return inertia('NebulaSenderNew', [
            'settings' => \Dniccum\CustomEmailSender\Library\ConfigUtility::config()
        ]);
    }

    public function nebulaSenderDrafts(NovaRequest $request)
    {
        return inertia('NebulaSenderDrafts', [
            'settings' => \Dniccum\CustomEmailSender\Library\ConfigUtility::config()
        ]);
    }

    public function nebulaSenderSentMessages(NovaRequest $request)
    {
        return inertia('NebulaSenderSent', [
            'settings' => \Dniccum\CustomEmailSender\Library\ConfigUtility::config()
        ]);
    }
}