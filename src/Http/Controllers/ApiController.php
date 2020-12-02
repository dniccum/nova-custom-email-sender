<?php


namespace Dniccum\CustomEmailSender\Http\Controllers;


trait ApiController
{
    protected $apiRoute;

    /**
     * @var string
     */
    protected $key;

    /**
     * NebulaSenderController constructor.
     */
    public function __construct()
    {
        $this->apiRoute = env('NEBULA_SENDER_API_ROUTE', 'https://nebulasender.com/api');
        $this->key = config('novaemailsender.nebula_sender.key');
    }
}