<?php

namespace Dniccum\CustomEmailSender\Library;

class ConfigUtility
{
    /**
     * @return array
     */
    public static function config() : array
    {
        $configuration = config('novaemailsender');

        /**
         * @var string[]|array $configurationOptions
         */
        $configurationFromOptions = config('novaemailsender.from.options');

        $fromOptions = collect($configurationFromOptions)->map(function($sender) {
            return [
                'address' => $sender['address'],
                'name' => $sender['name'] . ' (' . $sender['address'] . ')',
            ];
        });
        if($user = self::getAuthUserSender()){
            $user['name'] = $user['name']?  __('Me') . ' (' . $user['name'] . ' | ' . $user['address'] . ')' : '—';
            $fromOptions->push($user);
        }

        $configurationFromOptions = $fromOptions->toArray();
        $configuration['from']['options'] = $configurationFromOptions;

        $nebulaSenderActive = NebulaSenderUtility::isActive();

        return [
            'config' => $configuration,
            'messages' => array_merge(__('custom-email-sender::tool'), __('custom-email-sender::nebula-sender')),
            'nebula_sender_active' => $nebulaSenderActive,
        ];
    }

    /**
     * @return null[]|null
     */
    public static function getAuthUserSender()
    {
        $user = request()->user();

        if ($user) {
            if (config('novaemailsender')) {
                $email = 'email';
                $name = 'first_name';

                if (config('novaemailsender.model.email')) {
                    $email = config('novaemailsender.model.email');
                }
                if (config('novaemailsender.model.name')) {
                    $name = 'name';
                } elseif (config('novaemailsender.model.first_name')) {
                    $name = 'first_name';
                }

                return [
                    'address' => $user->$email ?? null,
                    'name' => $user->$name ?? null
                ];
            }
        }

        return null;
    }
}