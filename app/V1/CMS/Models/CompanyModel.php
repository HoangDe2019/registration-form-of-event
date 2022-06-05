<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Company;
use App\OFFICE;
use App\Supports\Message;

class CompanyModel extends AbstractModel
{
    public function __construct(Company $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $phone = "";
        if (!empty($input['phone'])) {
            $phone = str_replace(" ", "", $input['phone']);
            $phone = preg_replace('/\D/', '', $phone);
        }
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $company = Company::find($id);
            if (empty($company)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $company->code = array_get($input, 'code', $company->code);
            $company->name = array_get($input, 'name', $company->name);
            $company->email = array_get($input, 'email', $company->email);
            $company->address = array_get($input, 'address', $company->address);
            $company->tax = array_get($input, 'tax', $company->tax);
            $company->phone = array_get($phone, 'tax', $company->phone);
            $company->description = array_get($input, 'description', $company->description);
            $company->avatar_id = array_get($input, 'avatar_id', $company->avatar_id);
            $company->avatar = array_get($input, 'avatar', $company->avatar);
            $company->is_active = array_get($input, 'is_active', $company->is_active);
            $company->updated_at = date("Y-m-d H:i:s", time());
            $company->updated_by = OFFICE::getCurrentUserId();
            $company->save();
        } else {
            $param = [
                'code' => $input['code'],
                'name' => $input['name'],
                'email' => $input['email'],
                'address' => $input['address'],
                'tax' => $input['tax'],
                'phone' => $input['phone'],
                'description' => array_get($input, 'description'),
                'avatar_id' => array_get($input, 'avatar_id'),
                'avatar' => array_get($input, 'avatar'),
                'is_active' => 1,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $company = $this->create($param);
        }
        return $company;
    }
}
