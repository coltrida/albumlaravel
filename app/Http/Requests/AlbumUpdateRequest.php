<?php

namespace App\Http\Requests;

use App\Models\Album;
use const false;
use Illuminate\Foundation\Http\FormRequest;
use const true;
use Gate;

class AlbumUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $album = Album::find($this->id);
/*        if(Gate::denies('manage-album', $album)){
            return false;
        }*/

        if(Gate::denies('update', $album)){
            return false;
        }

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
