<?php


namespace Dniccum\CustomEmailSender\Http\Controllers;


use Dniccum\CustomEmailSender\Http\Requests\CreateDraft;
use Dniccum\CustomEmailSender\Http\Requests\UpdateDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NebulaSenderDraftController
{
    use ApiController;

    /**
     * Gets a list of available drafts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        $offset = $request->get('offset');

        $response = Http::withToken($this->key)
            ->get($this->apiRoute.'/draft', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

        return response()->json([
            'data' => $response->json('data')
        ], $response->status());
    }

    /**
     * Retrieves an existing draft
     *
     * @param int $draft
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $draft, Request $request)
    {
        $response = Http::withToken($this->key)
            ->get($this->apiRoute.'/draft/'.$draft);

        return response()->json([
            'data' => $response->json('data')
        ], $response->status());
    }

    /**
     * Creates a new draft
     *
     * @param CreateDraft $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateDraft $request)
    {
        $response = Http::withToken($this->key)
            ->post($this->apiRoute.'/draft', $request->validated());

        return response()->json([
            'data' => $response->json('data')
        ], $response->status());
    }

    /**
     * Updates an existing draft with new/modified content
     *
     * @param int $draft
     * @param UpdateDraft $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $draft, UpdateDraft $request)
    {
        $response = Http::withToken($this->key)
            ->put($this->apiRoute.'/draft/'.$draft, $request->validated());

        return response()->json([
            'data' => $response->json('data')
        ], $response->status());
    }

    /**
     * Deletes a draft
     *
     * @param int $draft
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $draft)
    {
        $response = Http::withToken($this->key)
            ->delete($this->apiRoute.'/draft/'.$draft);

        return response()->json([
            'data' => $response->json('data')
        ], $response->status());
    }
}