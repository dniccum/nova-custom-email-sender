<?php
namespace Dniccum\CustomEmailSender\Http\Controllers;

use Dniccum\CustomEmailSender\Http\Requests\SendCustomEmailMessage;
use Dniccum\CustomEmailSender\Library\UserUtility;
use Dniccum\CustomEmailSender\Mail\CustomMessageMailable;

class CustomEmailSenderController
{
    /**
     * @var UserUtility
     */
    private $userUtility;

    public function __construct()
    {
        $userClassName = config('novaemailsender.model.class');

        if (empty($userClassName)) {
            die('Please define a user class for the Custom Email Sender');
        }

        $this->userUtility = new UserUtility(new $userClassName);
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
        return response()
            ->json([
                'config' => config('novaemailsender'),
                'messages' => __('custom-email-sender::tool')
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
            $users = collect($requestData['recipients'])->map(function($address) {
                return ['email' => $address];
            });
        }

        $content = $requestData['htmlContent'];
        $subject = $requestData['subject'];

        $users->map(function($user) use ($content, $subject) {
            \Mail::to($user)
                ->send(new CustomMessageMailable($subject, $content));
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
}