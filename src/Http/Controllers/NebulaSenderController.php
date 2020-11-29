<?php


namespace Dniccum\CustomEmailSender\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NebulaSenderController
{
    private $apiRoute;

    /**
     * @var string
     */
    private $key;

    /**
     * NebulaSenderController constructor.
     */
    public function __construct()
    {
        $this->apiRoute = env('NEBULA_SENDER_API_ROUTE', 'https://nebulasender.com/api');
        $this->key = config('novaemailsender.nebula_sender.key');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function drafts(Request $request)
    {
        $response = Http::withToken($this->key)
            ->get($this->apiRoute.'/draft');

        return response()->json([
            'data' => $response->json('data')
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function messages(Request $request)
    {
        $response = Http::withToken($this->key)
            ->get($this->apiRoute.'/message');

        return response()->json([
            'data' => $response->json('data')
        ]);
    }
}