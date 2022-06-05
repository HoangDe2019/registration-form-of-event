<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Company;

use App\Company;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        try {
            return [
                'id' => $company->id,
                'code' => $company->code,
                'name' => $company->name,
                'email' => $company->email,
                'address' => $company->address,
                'tax' => $company->tax,
                'phone' => $company->phone,
                'description' => $company->description,
                'avatar_id' => $company->avatar_id,
                'avatar' => $company->avatar,
                'is_active' => $company->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($company->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($company->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
