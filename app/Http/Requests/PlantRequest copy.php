<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string|',
            'price' => 'required|numeric',
            'photo' => 'nullable',
        ];
    }

    //  /**
    //  * Custom message for validation
    //  *
    //  * @return array
    //  */
    // public function messages()
    // {
    //     return [
    //         // 'name.required' => 'Name is required!!',
    //         // 'description.required' => 'Description is required!!',
    //         // 'price.number_format' => 'Price must be Number',
    //         // 'plant_photo_path.exif_imagetype' => 'Image must be png/jpeg/jpg/svg/gif',
    //     ];
    // }
}
