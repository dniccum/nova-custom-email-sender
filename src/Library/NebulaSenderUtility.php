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

    /**
     * @param string $subject
     * @param string $template
     * @param array|Object[] $recipients
     * @param string $content
     * @param string $fullHtml
     * @return void
     */
    public static function logSentMessage(string $subject, string $template, array $recipients, string $content, string $fullHtml) : void
    {
        if (config('novaemailsender.nebula_sender_key')) {
            $route = env('NEBULA_SENDER_API_ROUTE', 'https://nebulasender.com/api');

            Http::withToken(config('novaemailsender.nebula_sender_key'))
                ->post($route.'/message', [
                    'subject' => $subject,
                    'template' => $template,
                    'recipients' => $recipients,
                    'content' => $content,
                    'full_html' => $fullHtml
                ]);
        }
    }
}