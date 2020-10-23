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
            $response = Http::withToken(config('novaemailsender.nebula_sender_key'))
                ->get('https://nebulasender.com/api/validate');

            $nebulaSenderActive = $response->status() === 200;
        }

        return $nebulaSenderActive;
    }
}