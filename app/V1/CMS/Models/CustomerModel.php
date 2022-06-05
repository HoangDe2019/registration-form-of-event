<?php
/**
 * User: Administrator
 * Date: 21/12/2018
 * Time: 07:51 PM
 */

namespace App\V1\CMS\Models;


use App\Customer;
use App\CustomerProfile;
use App\Order;
use App\OrderDetail;
use App\SSC;
use App\Supports\Message;
use Illuminate\Support\Facades\DB;
use App\V1\CMS\Models\UserModel;


class CustomerModel extends AbstractModel
{
    public function __construct(Customer $model = null)
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

        $y = date('Y', time());
        $m = date("m", time());
        $d = date("d", time());
        $dir = !empty($input['avatar']) ? "$y/$m/$d" : null;
        $file_name = empty($dir) ? null : "avatar_{$input['phone']}";
        if ($file_name) {
            $avatars = explode("base64,", $input['avatar']);
            $input['avatar'] = $avatars[1];
            if (!empty($file_name) && !is_image($avatars[1])) {
                return $this->response->errorBadRequest(Message::get("V002", "Avatar"));
            }
        }

        DB::beginTransaction();

        $id = !empty($input['id']) ? $input['id'] : 0;

        if ($id) {
            // Update Customer
            $param['id'] = $id;
            $customer = Customer::find($id);
            $customer->code = array_get($input, 'code', $customer->code);
            $customer->name = array_get($input, 'name', $customer->name);
            $customer->card_name = strtoupper(trim(array_get($input, 'card_name', $customer->card_name)));
            $customer->sscid = array_get($input, 'sscid', $customer->sscid);
            $customer->email = array_get($input, 'email', $customer->email);
            $customer->password = !empty($input['password']) ? password_hash($input['password'],
                PASSWORD_BCRYPT) : $customer->password;
            $customer->phone = !empty($phone) ? $phone : $customer->phone;
            $customer->note = array_get($input, 'note', $customer->note);
            $customer->is_seller = array_get($input, 'is_seller', $customer->is_seller);
            $customer->group_id = array_get($input, 'group_id', $customer->group_id);
            $customer->type_id = array_get($input, 'type_id', $customer->type_id);
            $customer->is_agency = array_get($input, 'is_agency', $customer->is_agency);
            $customer->updated_at = date("Y-m-d H:i:s", time());
            $customer->updated_by = SSC::getCurrentUserId();
            $customer->save();
        } else {
            $param = [
                'phone' => $phone,
                'code' => $input['code'],
                'name' => $input['name'],
                'card_name' => strtoupper(trim(array_get($input, 'card_name', $input['name']))),
                'sscid' => $input['sscid'],
                'group_id' => array_get($input, 'group_id'),
                'type_id' => array_get($input, 'type_id'),
                'email' => array_get($input, 'email'),
                'verify_code' => mt_rand(100000, 999999),
                'expired_code' => date('Y-m-d H:i:s', strtotime("+5 minutes")),
                'is_active' => array_get($input, 'is_active', 1),
                'is_agency' => array_get($input, 'is_agency', 1)
            ];
            $param['password'] = !empty($input['password']) ?
                password_hash($input['password'], PASSWORD_BCRYPT) :
                password_hash(config('constants.customer_password_default'), PASSWORD_BCRYPT);

            // Create Customer
            $customer = $this->create($param);
            $user = new UserModel;
            $user->upsert($input);

        }

        $profile = CustomerProfile::where(['customer_id' => $customer->id])->first();
        $names = explode(" ", trim($input['name']));
        $first = $names[0];
        unset($names[0]);
        $last = !empty($names) ? implode(" ", $names) : null;

        $prProfile = [
            'email' => array_get($input, 'email'),
            'is_active' => 1,
            'first_name' => $first,
            'last_name' => $last,
            'short_name' => $input['name'],
            'full_name' => $input['name'],
            'branch_name' => array_get($input, 'branch_name', null),
            'address' => array_get($input, 'address', null),
            'receipt_address' => array_get($input, 'receipt_address', null),
            'phone' => array_get($input, 'phone', null),
            'birthday' => empty($input['birthday']) ? null : $input['birthday'],
            'genre' => array_get($input, 'genre', "O"),
            'avatar' => $file_name ? $dir . "/" . $file_name . ".jpg" : null,
            'account_number' => array_get($input, 'account_number'),
            'tax_number' => array_get($input, 'tax_number'),
            'bank_type' => array_get($input, 'bank_type'),
            'spokesman' => array_get($input, 'spokesman'),
            'id_number' => array_get($input, 'id_number', 0),
            'customer_id' => $customer->id
        ];

        // Create Profile
        $customerProfileMode = new CustomerProfileModel();
        if (empty($profile)) {
            $customerProfileMode->create($prProfile);
        } else {
            $prProfile['id'] = $profile->id;
            $customerProfileMode->update($prProfile);
        }

        DB::commit();

        return $customer;
    }

    public function checkCanDelete($customerId)
    {
        $order = Order::model()->where('customer_id', $customerId)->get()->toArray();
        if (empty($order)) {
            return true;
        }

        foreach ($order as $item) {
            if (in_array($item['status'], ['ACCEPTED', 'SHIPPING', 'RECEIVED'])) {
                return false;
            }
        }
        return true;
    }
}
