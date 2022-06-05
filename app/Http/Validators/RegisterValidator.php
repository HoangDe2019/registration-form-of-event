<?php
/**
 * User: Administrator
 * Date: 10/10/2018
 * Time: 07:40 PM
 */

namespace App\Http\Validators;


use App\Supports\Message;

class RegisterValidator extends ValidatorBase
{
    protected function attributes()
    {
        return [
            'phone' => Message::get("phone"),
            'device_id' => Message::get("device_id"),
            'device_type' => Message::get("device_type"),
            'name' => Message::get("name"),
            'city_id' => Message::get("city_id"),
            'district_id' => Message::get("district_id"),
            'ward_id' => Message::get("ward_id"),
            'password' => Message::get("password"),
        ];
    }

    protected function rules()
    {
        return [
            'device_id' => 'required',
            'device_type' => 'required',
            'phone' => 'required|numeric|not_in:0|unique:users,phone',
            'name' => 'required|min:5|max:40',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'exists:districts,id',
            'ward_id' => 'exists:wards,id',
            'password' => 'required|min:8',
        ];
    }


}