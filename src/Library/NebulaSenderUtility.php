<?php


namespace Dniccum\CustomEmailSender\Library;


use Illuminate\Support\Facades\Http;

class NebulaSenderUtility
{
    /**
     * @return bool
     */
    public static function isActive() : bool
    {
        $nebulaSenderActive = false;
        if (config('novaemailsender.nebula_sender_key')) {
            $route = env('NEBULA_SENDER_API_ROUTE', 'https://nebulasender.com/api');

            $response = Http::withToken(config('novaemailsender.nebula_sender_key'))
                ->get($route.'/validate');

            $nebulaSenderActive = $response->status() === 200;
        }

        return $nebulaSenderActive;
    }
}