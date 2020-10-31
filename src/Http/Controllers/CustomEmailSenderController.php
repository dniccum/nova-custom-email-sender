<?php
namespace Dniccum\CustomEmailSender\Http\Controllers;

use Dniccum\CustomEmailSender\Http\Requests\SendCustomEmailMessage;
use Dniccum\CustomEmailSender\Library\NebulaSenderUtility;
use Dniccum\CustomEmailSender\Library\UserUtility;
use Dniccum\CustomEmailSender\Mail\CustomMessageMailable;
use Illuminate\Http\Request;

class CustomEmailSenderController
{
    /**
     * @var UserUtility
     */
    private $userUtility;

    public function __construct()
    {
        $userClassNames = config('novaemailsender.model.classes');

        if (empty($userClassNames)) {
            die('Please define a user class for the Custom Email Sender');
        }

        $this->userUtility = new UserUtility($userClassNames);
    }

    /**
     * Returns the current configuration to be used in the
     * user interface
     *
     * @todo validate the configuration and provide errors if necessary
     * @return \Illuminate\Http\JsonResponse
     */
    public function config()
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
        if($user = $this->getAuthUserSender()){
            $user['name'] = $user['name']?  __('Me') . ' (' . $user['name'] . ' | ' . $user['address'] . ')' : '—';
            $fromOptions->push($user);
        }

        $configurationFromOptions = $fromOptions->toArray();
        $configuration['from']['options'] = $configurationFromOptions;

        $nebulaSenderActive = NebulaSenderUtility::isActive();

        return response()
            ->json([
                'config' => $configuration,
                'messages' => array_merge(__('custom-email-sender::tool'), __('custom-email-sender::nebula-sender')),
                'nebula_sender_active' => $nebulaSenderActive,
            ]);
    }

    /**
     * Sends the messages to the requested users.
     *
     * @param SendCustomEmailMessage $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SendCustomEmailMessage $request)
    {
        $requestData = $request->validated();

        if ($requestData['sendToAll']) {
            $users = $this->userUtility->getAllUsers();
        } else {
            $users = collect($requestData['recipients'])->map(function($recipient) {
                return [
                    'email' => $recipient['email'],
                ];
            });
        }

        $sender = collect( config('novaemailsender.from.options') )
                ->push($this->getAuthUserSender()) // remember the auth select option
                ->firstWhere('address', $requestData['from']);
        $content = $requestData['htmlContent'];
        $subject = $requestData['subject'];

        $users->each(function($user) use ($content, $subject, $sender) {
            \Mail::to($user)
                 ->send(new CustomMessageMailable($subject, $content, $sender));
        });

        return response()->json($users->count(). ' '.__('custom-email-sender::tool.emails-sent'), 200);
    }

    public function preview(SendCustomEmailMessage $request)
    {
        $requestData = $request->validated();

        $content = $requestData['htmlContent'];
        $subject = $requestData['subject'];

        $email = new CustomMessageMailable($subject, $content);

        return response()->json([
           'content' => $email->render()
        ], 200);
    }

    /**
     * Search results
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $results = $this->userUtility->searchUsers($query);

        return response()->json($results, 200);
    }

    /**
     * Get auth user
     *
     * @return array
     */
    private function getAuthUserSender()
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