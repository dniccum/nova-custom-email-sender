<?php


namespace Dniccum\CustomEmailSender\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NebulaSenderController
{
    private $apiRoute;

    /**
     * NebulaSenderController constructor.
     */
    public function __construct()
    {
        $this->apiRoute = env('NEBULA_SENDER_API_ROUTE', 'https://nebulasender.com/api');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function drafts(Request $request)
    {
        $response = Http::withToken(config('novaemailsender.nebula_sender_key'))
            ->get($this->apiRoute.'/draft');

        return response()->json([
            'data' => $response->json('data')
        ]);
    }
}