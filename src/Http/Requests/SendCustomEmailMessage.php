<?php
namespace Dniccum\CustomEmailSender\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendCustomEmailMessage extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxCharacters = config('novaemailsender.validation.max-characters') ?? 2000;
        return [
            'from' => 'string|required',
            'subject' => 'string|required',
            'sendToAll' => 'boolean|required_without:recipients',
            'recipients' => 'required_without:sendToAll|array',
            'htmlContent' => "required|string|max:$maxCharacters",
        ];
    }

}
