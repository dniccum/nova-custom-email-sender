<?php


namespace Dniccum\CustomEmailSender\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDraft extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required|string|max:150',
            'subject' => 'nullable|max:100',
            'template' => 'required|max:100',
            'recipients' => 'nullable|array',
            'send_to_all' => 'nullable|boolean',
            'content' => 'required|string|max:10000',
        ];
    }
}