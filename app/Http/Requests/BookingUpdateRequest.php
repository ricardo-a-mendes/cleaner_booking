<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $dataValidation = [
            'date' => 'date_format:Y-m-d H:i|after:today'
        ];
        if ($this->request->get('_method') === 'POST') {
            $dataValidation = [
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'phone_number' => 'required|regex:/\d{2}[\-]\d{3}[\-]\d{4}/',
                'city' => 'required'
            ];
        }

        return $dataValidation;
    }
}
