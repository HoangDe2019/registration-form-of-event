<?php


namespace App\V1\CMS\Validators;


use App\OFFICE;
use App\Http\Validators\ValidatorBase;
use App\Profile;
use App\Supports\Message;
use Illuminate\Http\Request;

class UserDoctorUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'email' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $input = Request::capture();
                        $item = Profile::model()->where('email', $value)->get();
                        $userId = OFFICE::getCurrentUserId();
                        if (!empty($item) && count($item) > 0) {
                            if (count($item) > 1 || ($item[0]['user_id'] != $userId)) {
                                return $fail(Message::get("unique", "$attribute: $value"));
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
                                return $fail(Message::get("unique", "$attribute: $value"));
                            }
                        }
                    }
                    return true;
                },
            ],
            'full_name' => 'nullable|max:100',
            'birthday' => 'nullable|date_format:Y-m-d',
            'username' => 'nullable|unique_update:users,username',
            'genre' => 'nullable|max:8',
            'address' => 'nullable|max:500',
            'department_id' => '|exists:departments,id,deleted_at,NULL',
            'role_id' => '|exists:roles,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'phone' => Message::get("phone"),
            'email' => Message::get("email"),
            'username' => Message::get("username")
        ];
    }
}