<?php
/**
 * User: Administrator
 * Date: 16/10/2018
 * Time: 08:12 PM
 */

namespace App\V1\CMS\Models;


use App\Contact;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

class ContactModel extends AbstractModel
{
    /**
     * ContactModel constructor.
     * @param Contact|null $model
     */
    public function __construct(Contact $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $param = [
            'code' => strtoupper(array_get($input, 'code')),
            'name' => $input['name'],
            'description' => array_get($input, 'description', null),
            'group_id' => array_get($input, 'group_id'),
            'is_active' => 1,
        ];

        try {
            DB::beginTransaction();
            $id = !empty($input['id']) ? $input['id'] : 0;
            if (!empty($input['code'])) {
                $this->checkUnique([
                    'code' => strtoupper($input['code']),
                ], $id);
            }

            if ($id) {
                $param['id'] = $input['id'];
                $contact = $this->update($param);
            } else {
                // Create
                $contact = $this->create($param);
            }

            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message']);
        }

        return $contact;
    }
}