<?php
namespace Dniccum\CustomEmailSender\Library;


class UserUtility
{

    private $model;

    /**
     * UserUtility constructor.
     * @param Model|mixed $userClass
     */
    public function __construct($userClass)
    {
        $this->model = new $userClass;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllUsers()
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