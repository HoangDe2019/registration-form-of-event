<?php


namespace App\V1\CMS\Validators;


use App\OFFICE;
use App\Http\Validators\ValidatorBase;
use App\Profile;
use App\Supports\Message;
use Illuminate\Http\Request;

class UserProfileUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'email' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $input = Request::capture();
                        $item = Profile::model()->where('email', $value)->get();
                        $userId = OFFICE::getCurrentUserId();
                        if (!empty($item) && count($item) > 0) {
                            if (count($item) > 1 || ($item[0]['user_id'] != $userId)) {
                                return $fail(Message::get("unique", "$attribute: #$value"));
                            }
                        }
                    }
                    return true;
                },
            ],
            'phone' => [
                'nullable',
                'nullable',
                'max:12',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $input = Request::capture();
                        $item = Profile::model()->where('phone', $value)->get();
                        $userId = OFFICE::getCurrentUserId();
                        if (!empty($item) && count($item) > 0) {
                            if (count($item) > 1 || ($item[0]['user_id'] != $userId)) {
                                return $fail(Message::get("unique", "$attribute: #$value"));
                            }
                        }
                    }
                    return true;
                },
            ],
            'first_name' => 'nullable|max:100',
            'last_name' => 'nullable|max:100',
            'address' => 'nullable|max:500',
            'birthday' => 'nullable|date_format:Y-m-d',
            'password' => 'nullable|min:8',
        ];
    }

    protected function attributes()
    {
        return [
            'user_id' => Message::get("users"),
            'phone' => Message::get("phone"),
            'email' => Message::get("email"),
            'first_name' => Message::get("alternative_name"),
            'last_name' => Message::get("alternative_name"),
            'password' => Message::get("password"),
            'address' => Message::get("address"),
            'birthday' => Message::get("birthday"),
        ];
    }
}