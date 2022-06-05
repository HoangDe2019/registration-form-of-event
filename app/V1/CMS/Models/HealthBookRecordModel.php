<?php

/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 09:32 PM
 */

namespace App\V1\CMS\Models;
use App\Company;
use App\HealthBookRecord;
use App\UserHealthBookRecord;
use App\Profile;
use App\Supports\Message;
use App\User;
use Illuminate\Support\Facades\DB;
use App\OFFICE;

class HealthBookRecordModel extends AbstractModel
{
    public function __construct(HealthBookRecord $model = null)
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
        if (!empty($input['avatar'])) {
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $path = public_path('uploads');
            if (!file_exists("$path/$y/$m/$d")) {
                mkdir("$path/$y/$m/$d", 0777, true);
            }
            $avatar = explode("base64,", $input['avatar']);
            if (!is_image($avatar[1])) {
                throw new \Exception(Message::get("V002", "Avatar"));
            }
            $imgData = base64_decode($avatar[1]);
            $fileName = strtoupper(uniqid()) . ".png";
            $filePath = "$path/$y/$m/$d/$fileName";
            $partSaveProfile = str_replace("/", ',', "$y/$m/$d/$fileName");
            file_put_contents($filePath, $imgData);
        }
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $result = HealthBookRecord::find($id);
            $result->active_at = date("Y-m-d H:i:s", time());
        } else {
            //Create User
            $paramUser = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone'),
                'email' => array_get($input, 'email'),
                'username' => array_get($input, 'phone'),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT),
                'is_active' => 1,
                'role_id' => 5,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $userModel = new User();
            $user = $userModel->create($paramUser);
            //Create Profile
            if (!empty($input['full_name'])) {
                $names = explode(" ", trim($input['full_name']));
                $last = $names[0];
                unset($names[0]);
                $first = !empty($names) ? implode(" ", $names) : null;
            }
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'address' => empty($input['address']) ? null:$input['address'],
                'first_name' => $first,
                'avatar' => $partSaveProfile ?? null,
                'last_name' => $last,
                'genre' => array_get($input, 'genre', 'O'),
                'blood_group' =>array_get($input, 'blood_group', 'A+'),
                'birthday' => empty($input['birthday']) ? null : $input['birthday'],
                'phone' => $input['phone'],
                'email' => $input['email'],
            ];
            $profileModel->create($profileParam);
            //HealthBookRecord
            $healthBookModel = new HealthBookRecord();
            $healthBookParam = [
                'user_id' => $user->id,
                'action' => date("Y-m-d", time()),
                'active_at'=> date("Y-m-d H:i:s", time()),
                'is_active' => 1,
            ];
            $result = $healthBookModel->create($healthBookParam);
        }
        $result->save();
        return $result;
    }

    public function search($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (!empty($input['role_code'])){
            $query=$query->whereHas('user',function ($q) use ($input){$q->where('role_id', $input['role_code']);});
        }
        if(!empty($input['month_begin'])){
            $query = $query->where('updated_at', '>=', $input['month_begin']);
        }
        
        if(!empty($input['full_name'])){
            $query=$query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($qp) use ($input) {
                    $qp->where('full_name', 'like', "%{$input['full_name']}");
                });
            });
        }

        if(!empty($input['phone_number'])){
            $query=$query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($qp) use ($input) {
                    $qp->where('phone', 'like', "%{$input['phone_number']}");
                });
            });
        }

        if(!empty($input['month_end'])){
            $query = $query->where('updated_at', '<=', $input['month_end']);
        }

        $query = $query->whereNull('deleted_at')->orderBy('updated_at', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }
}
