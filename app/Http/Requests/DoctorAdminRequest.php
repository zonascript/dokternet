<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DoctorAdminRequest extends Request
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
            //
            'user_id' => 'required',
            'specialization_id' => 'required',
            'city_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'email' => 'required',
            'password' => 'required',
            'mobile' => 'required',
            'telephone' => 'required',
        ];
    }
}