<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Request;

class StoreBookRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "user_id" => "required|numeric",
            "title" => "required",
            "author" => "required",
            "kind" => "required|numeric",
            "isbn" => "required|unique:books,isbn",
            "year_of" => "required|numeric",
        ];
    }

    public function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
            ])
        );

    }

    public function  messages()
    {
        return [
            "user_id.required" => "Il campo user è obbligatorio",
            "user_id.numeric" => "Il campo user deve essere numerico",
            "title.required" => "Il campo titolo è obbligatorio",
            "author.required" => "Il campo autore è obbligatorio",
            "kind.required" => "Il campo genere è obbligatorio",
            "kind" => "Il campo genere deve essere numerico",
            "isbn.required" => "Il campo isbn è obbligatorio",
            "isbn.unique" => "Il valore isbn inserito è già presente",
            "year_of.required" => "Il campo anno di pubblicazione è obbligatorio",
            "year_of.numeric" => "Il campo deve essere numerico",
        ];
    }


}
