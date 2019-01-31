<?php
namespace Dniccum\CustomEmailSender\Http\Controllers;

use Dniccum\CustomEmailSender\Http\Requests\SendCustomEmailMessage;

class CustomEmailSenderController
{
    private $model;

    private $view;

    public function __construct()
    {
        $userClassName = config('novaemailsender.model.class');

        if (empty($userClassName)) {
            die('Please define a user class for the Custom Email Sender');
        }

        $this->model = new $userClassName;

        $this->view = config('novaemailsender.template.view');

        if (!view()->exists($this->view)) {
            \View::addLocation(base_path('vendor/dniccum/custom-email-sender/resources/views'));
            $this->view = 'email';
        }
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

        $content = $this->convertContent($requestData['htmlContent']);
        $subject = $requestData['subject'];

        $users->map(function($user) use ($content, $subject) {

            // TODO figure out a way to build the markdown view

            try {
                \Mail::send($this->view, $content, function($message) use ($user, $subject) {
                    $message
                        ->from(config('novaemailsender.from.address'), config('novaemailsender.from.name'))
                        ->subject($subject);

                    $message->to($user->email);
                });
            } catch (\Exception $e) {
                \Log::debug('email could not be sent', [
                    'user' => $user,
                    'error' => $e
                ]);
            }
        });

        return response()->json('Email(s) have been sent successfully.', 200);
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

    /**
     * @param string $htmlContent
     * @return array
     */
    private function convertContent(string $htmlContent)
    {
        return [
            'content' => $htmlContent,
        ];
    }
}