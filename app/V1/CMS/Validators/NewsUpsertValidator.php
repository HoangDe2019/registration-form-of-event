<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;

/**
 * Class NewsUpsertValidator
 *
 * @package App\V1\CMS\Validators
 */
class NewsUpsertValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:news,id,deleted_at,NULL',
            'title' => 'required|max:150',
            'short_description' => 'max:300',
            'thumbnail' => 'max:150',
            'image' => 'max:150',
            'published_date' => 'date_format:Y-m-d',
            'is_approved' => 'int|max:1',
            'is_active' => 'int|max:1',
        ];
    }

    protected function attributes()
    {
        return [

        ];
    }
}