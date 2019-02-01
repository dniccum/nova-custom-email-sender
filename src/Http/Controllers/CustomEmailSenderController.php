<?php
namespace Dniccum\CustomEmailSender\Http\Controllers;

use Dniccum\CustomEmailSender\Http\Requests\SendCustomEmailMessage;
use Dniccum\CustomEmailSender\Mail\CustomMessageMailable;

class CustomEmailSenderController
{
    private $model;

    public function __construct()
    {
        $userClassName = config('novaemailsender.model.class');

        if (empty($userClassName)) {
            die('Please define a user class for the Custom Email Sender');
        }

        $this->model = new $userClassName;
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
            ->json(config('novaemailsender'));
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
            $users = $this->getAllUsers();
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

        return response()->json($users->count(). ' email(s) have been sent.', 200);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getAllUsers()
    {
        $selectQuery = $this->selectQuery();
        $userCollection = collect([]);

        $this->model->select($selectQuery)->chunk(200, function($users) use ($userCollection) {
            foreach ($users as $user) {
                $email = config('novaemailsender.model.email');

                if (!empty(config('novaemailsender.model.name'))) {
                    $nameProperty = config('novaemailsender.model.name');
                    $name = $user->$nameProperty;
                } else {
                    $firstNameProperty = config('novaemailsender.model.first_name');
                    $lastNameProperty = config('novaemailsender.model.last_name');
                    $name = $user->$firstNameProperty.' '.$user->$lastNameProperty;
                }

                $object = new \stdClass();
                $object->email = $user->$email;
                $object->name = $name;

                $userCollection->push($object);
            }
        });

        return $userCollection;
    }

    /**
     * @return array|string[]
     */
    private function selectQuery()
    {
        $selectQuery = [ 'id', config('novaemailsender.model.email') ];

        if (!empty(config('novaemailsender.model.name'))) {
            $selectQuery[] = 'name';
        } else {
            $selectQuery[] = config('novaemailsender.model.first_name');
            $selectQuery[] = config('novaemailsender.model.last_name');
        }

        return $selectQuery;
    }
}