<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use const true;

class AlbumRequest extends FormRequest
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
        return [
            'name' => 'required|unique:albums,album_name',
            'description' => 'required',
            'album_thumb' => 'required|image',
            //'user_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
                'name.required' => 'Il nome dell\'album è obbligatorio',
                'album_thumb.required' => 'immagine dell\'album è obbligatoria',
                'description.required' => 'La descrizione obbligatoria',
        ];
    }
}
