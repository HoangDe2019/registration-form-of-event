<?php

/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 09:32 PM
 */

namespace App\V1\CMS\Models;

use App\Company;
use App\Education;
use App\HealthBookRecord;
use App\UserHealthBookRecord;
use App\Profile;
use App\Supports\Message;
use App\User;
use Illuminate\Support\Facades\DB;
use App\OFFICE;
use Kreait\Firebase\Storage;
use Illuminate\Support\Facades\File;

class UserModel extends AbstractModel
{
    public function __construct(User $model = null)
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
        if (!empty($input['avatar'])) {
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $path = public_path('uploads');

            $profile= Profile::model()->where('user_id', '=', $id)->first();
            if(!empty($profile)) {
                $img = $profile['avatar'];
                $partSaveProfile = str_replace(",", '/', "$y/$m/$d/$img");
                $path = public_path('uploads');
                $filePath = "$path/$partSaveProfile";
                File::delete($filePath);
            }

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
        if ($id) {
            $user = User::find($id);
            if (empty($user)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            if (!empty($input['password'])) {
                $password = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            $user->phone = array_get($input, 'phone', $user->phone);
            $user->code = array_get($input, 'code', $user->code);
            $user->username = array_get($input, 'username', $user->username);
            $user->email = array_get($input, 'email', $user->email);
            $user->role_id = array_get($input, 'role_id', $user->role_id);
            $user->password = empty($password) ? array_get($input, 'password', $user->password) : $password;
            $user->verify_code = array_get($input, 'verify_code', $user->verify_code);
            $user->expired_code = array_get($input, 'expired_code', $user->expired_code);
            $user->is_active = array_get($input, 'is_active', $user->is_active);
            $user->updated_at = date("Y-m-d H:i:s", time());
            $user->updated_by = OFFICE::getCurrentUserId();
            $user->save();
        } else {
            $param = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone', null),
                'email' => array_get($input, 'email'),
                'username' => array_get($input, 'phone'),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT),
                'is_active' => 1,
                'role_id' => 1,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $user = $this->create($param);
        }
        //Finish Create User
        // Create or Update Profile
        $profile = Profile::model()->where('user_id', $user->id)->first();
        //if empty -> create
        //if !empty -> update
        if (!empty($input['full_name'])) {
            $names = explode(" ", trim($input['full_name']));
            $last = $names[0];
            unset($names[0]);
            $first = !empty($names) ? implode(" ", $names) : null;
        }
        if (empty($profile)) {
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'first_name' => $first,
                'avatar' => $partSaveProfile ?? null,
                'last_name' => $last,
                'phone' => $input['phone'],
                'email' => $input['email'],
            ];
            $profileModel->create($profileParam);
        } else {
            $profile->full_name = $input['full_name'];
            $profile->phone = $input['phone'];
            $profile->first_name = $first;
            $profile->last_name = $last;
            $profile->email = $input['email'];
            $profile->address = $input['address'];
            $profile->avatar = $partSaveProfile ?? null;
            $profile->save();
        }
        return $user;
    }

    //create user doctor
    public function upsertDoctorCreateOrUpdate($input)
    {
        $phone = "";
        if (!empty($input['phone'])) {
            $phone = str_replace(" ", "", $input['phone']);
            $phone = preg_replace('/\D/', '', $phone);
        }
        $id = !empty($input['id']) ? $input['id'] : 0;
        if (!empty($input['avatar'])) {
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $names = $input['phone'];
            $path = public_path('uploads');

            $profileDr= Profile::model()->where('user_id', '=', $id)->first();
            if(!empty($profileDr)) {
                $img = $profileDr['avatar'];
                $partSaveProfile = str_replace(",", '/', "$y/$m/$d/$img");
                $path = public_path('uploads');
                $filePath = "$path/$partSaveProfile";
                File::delete($filePath);
            }

            if (!file_exists("$path/$y/$m/$d/$names")) {
                mkdir("$path/$y/$m/$d/$names", 0777, true);
            }
            $avatar = explode("base64,", $input['avatar']);
            if (!is_image($avatar[1])) {
                throw new \Exception(Message::get("V002", "Avatar"));
            }
            $imgData = base64_decode($avatar[1]);
            $fileName = strtoupper(uniqid()) . ".png";
            $filePath = "$path/$y/$m/$d/$names/$fileName";
            $partSaveProfile = str_replace("/", ',', "$y/$m/$d/$names/$fileName");
            file_put_contents($filePath, $imgData);
        }
        if ($id) {
            $user = User::find($id);
            if (empty($user)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            if (!empty($input['password'])) {
                $password = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            $user->phone = array_get($input, 'phone', $user->phone);
            $user->code = array_get($input, 'code', $user->phone);
            $user->username = array_get($input, 'username', $user->username);
            $user->email = empty($input['email']) ? array_get($input, 'email', $user->email) : $input['email'];
            $user->role_id = array_get($input, 'role_id', $user->role_id);
            $user->department_id = array_get($input, 'department_id', $user->department_id);
            $user->password = empty($password) ? array_get($input, 'password', $user->password) : $password;
            $user->verify_code = array_get($input, 'verify_code', $user->verify_code);
            $user->expired_code = array_get($input, 'expired_code', $user->expired_code);
            $user->is_active = array_get($input, 'is_active', $user->is_active);
            $user->is_super = array_get($input, 'is_super', $user->is_super);
            $user->updated_at = date("Y-m-d H:i:s", time());
            $user->updated_by = OFFICE::getCurrentUserId();
            $user->save();
        } else {
            $param = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone', null),
                'email' => array_get($input, 'email'),
                'username' => array_get($input, 'email'),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT),
                'is_active' => 1,
                'is_super'=>array_get($input, 'is_super', 0),
                'department_id' =>  array_get($input, 'department_id'),
                'role_id' =>  array_get($input, 'role_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $user = $this->create($param);
        }
        //Finish Create User
        // Create or Update Profile
        $profile = Profile::model()->where('user_id', $user->id)->first();
        //if empty -> create
        //if !empty -> update
        if (!empty($input['full_name'])) {
            $names = explode(" ", trim($input['full_name']));
            $last = $names[0];
            unset($names[0]);
            $first = !empty($names) ? implode(" ", $names) : null;
        }
        if (empty($profile)) {
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'first_name' => $first,
                'avatar' => $partSaveProfile ?? null,
                'last_name' => $last,
                'address' => empty($input['address']) ? null:$input['address'],
                'blood_group' =>array_get($input, 'blood_group', 'A+'),
                'genre' => array_get($input, 'genre', 'O'),
                'birthday' => empty($input['birthday']) ? null : $input['birthday'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            //print_r($profileParam); die;

            $profileModel->create($profileParam);
        } else {
            $profile->full_name =empty($input['full_name']) ? array_get($input, 'full_name', $profile->full_name) : $input['full_name'];
            $profile->birthday =empty($input['birthday']) ? array_get($input, 'birthday', $profile->birthday) : $input['birthday'];
            $profile->phone = empty($input['phone']) ? array_get($input, 'phone', $profile->phone) : $input['phone'];
            $profile->first_name = empty($first) ? array_get($input, 'first_name', $profile->first_name) : $first;
            $profile->last_name = empty($last) ? array_get($input, 'last_name', $profile->last_name) : $last;
            $profile->genre = empty($input['genre']) ? array_get($input, 'genre', $profile->genre) : $input['genre'];
            $profile->blood_group = empty($input['blood_group']) ? array_get($input, 'blood_group', $profile->blood_group) : $input['blood_group'];
            $profile->email = empty($input['email']) ? array_get($input, 'email', $profile->email) : $input['email'];
            $profile->address = empty($input['address']) ? array_get($input, 'address', $profile->address) : $input['address'];
            $profile->avatar = empty($partSaveProfile) ? array_get($input, 'avatar', $profile->avatar) : $partSaveProfile;
            $profile->updated_at = date("Y-m-d H:i:s", time());
            $profile->updated_by = OFFICE::getCurrentUserId();
            $profile->save();
        }
        return $user;
    }

    //update for patients
    public function upsertInfo($input)
    {
        $userId = OFFICE::getCurrentUserId();
        $id = !empty($userId) ? $userId : 0;
        if (!empty($input['avatar'])) {
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $path = public_path('uploads');

            //check data img Patient
            $profilePt= Profile::model()->where('user_id', '=', $id)->first();
            if(!empty($profileDr)) {
                $img = $profilePt['avatar'];
                $partSaveProfile = str_replace(",", '/', "$y/$m/$d/$img");
                $path = public_path('uploads');
                $filePath = "$path/$partSaveProfile";
                File::delete($filePath);
            }

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
        if ($id) {
            $user = User::find($id);
            if (empty($user)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            if (!empty($input['password'])) {
                $password = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            $user->phone = empty($input['phone']) ? array_get($input, 'phone', $user->phone) : $input['phone'];
            $user->code = array_get($input, 'code', $user->code);
            //$user->username = array_get($input, 'username', $user->username);
            $user->email = empty($input['email']) ? array_get($input, 'email', $user->email) : $input['email'];
            $user->role_id = array_get($input, 'role_id', $user->role_id);
            $user->password = empty($password) ? array_get($input, 'password', $user->password) : $password;
            $user->verify_code = array_get($input, 'verify_code', $user->verify_code);
            $user->expired_code = array_get($input, 'expired_code', $user->expired_code);
            $user->is_active = array_get($input, 'is_active', $user->is_active);
            $user->updated_at = date("Y-m-d H:i:s", time());
            $user->updated_by = OFFICE::getCurrentUserId();
            $user->save();
        } else {
            $param = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone', null),
                'email' => array_get($input, 'email', null),
                'username' => array_get($input, 'phone'),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT),
                'is_active' => 1,
                'role_id' => 1,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $user = $this->create($param);
        }
        //Finish Create User
        // Create or Update Profile
        $profile = Profile::model()->where('user_id', $user->id)->first();
        //if empty -> create
        //if !empty -> update
        if (!empty($input['full_name'])) {
            $names = explode(" ", trim($input['full_name']));
            $last = $names[0];
            unset($names[0]);
            $first = !empty($names) ? implode(" ", $names) : null;
        }
        if (empty($profile)) {
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'first_name' => $first,
                'avatar' => $partSaveProfile ?? null,
                'last_name' => $last,
                'phone' => $input['phone'],
                'email' => $input['email'] ?? null,
            ];
            $profileModel->create($profileParam);
        } else {
            $profile->full_name =empty($input['full_name']) ? array_get($input, 'full_name', $profile->full_name) : $input['full_name'];
            $profile->birthday =empty($input['birthday']) ? array_get($input, 'birthday', $profile->birthday) : $input['birthday'];
            $profile->phone = empty($input['phone']) ? array_get($input, 'phone', $profile->phone) : $input['phone'];
            $profile->first_name = empty($first) ? array_get($input, 'first_name', $profile->first_name) : $first;
            $profile->last_name = empty($last) ? array_get($input, 'last_name', $profile->last_name) : $last;
            $profile->genre = empty($input['genre']) ? array_get($input, 'genre', $profile->genre) : $input['genre'];
            $profile->blood_group = empty($input['blood_group']) ? array_get($input, 'blood_group', $profile->blood_group) : $input['blood_group'];
            $profile->email = empty($input['email']) ? array_get($input, 'email', $profile->email) : $input['email'];
            $profile->address = empty($input['address']) ? array_get($input, 'address', $profile->address) : $input['address'];
            $profile->avatar = empty($partSaveProfile) ? array_get($input, 'avatar', $profile->avatar) : $partSaveProfile;
            $profile->updated_at = date("Y-m-d H:i:s", time());
            $profile->updated_by = OFFICE::getCurrentUserId();
            $profile->save();
        }
        return $user;
    }

    //Ho so bac si
    public function upsertDoctorInfo($input)
    {
        $userId = OFFICE::getCurrentUserId();
        $id = !empty($userId) ? $userId : 0;
        if (!empty($input['avatar'])) {
            $user = User::find($id);
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $path = public_path('uploads');

            $profileDr= Profile::model()->where('user_id', '=', $id)->first();
            if(!empty($profileDr)) {
                $img = $profileDr['avatar'];
                $partSaveProfile = str_replace(",", '/', "$y/$m/$d/$img");
                $path = public_path('uploads');
                $filePath = "$path/$partSaveProfile";
                File::delete($filePath);
            }

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
        if ($id) {
            $user = User::find($id);
            if (empty($user)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            if (!empty($input['password'])) {
                $password = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            $user->phone = empty($input['phone']) ? array_get($input, 'phone', $user->phone) : $input['phone'];
            $user->code = array_get($input, 'code', $user->code);
            //$user->username = array_get($input, 'username', $user->username);
            $user->email = empty($input['email']) ? array_get($input, 'email', $user->email) : $input['email'];
            $user->role_id = array_get($input, 'role_id', $user->role_id);
            $user->password = empty($password) ? array_get($input, 'password', $user->password) : $password;
            $user->verify_code = array_get($input, 'verify_code', $user->verify_code);
            $user->expired_code = array_get($input, 'expired_code', $user->expired_code);
            $user->is_active = array_get($input, 'is_active', $user->is_active);
            $user->updated_at = date("Y-m-d H:i:s", time());
            $user->updated_by = OFFICE::getCurrentUserId();
            $user->save();
        } else {
            $param = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone', null),
                'email' => array_get($input, 'email'),
                'username' => array_get($input, 'phone'),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT),
                'is_active' => 1,
                'role_id' => array_get($input, 'role_id', null),
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $user = $this->create($param);
        }
        //Finish Create User
        // Create or Update Profile
        $profile = Profile::model()->where('user_id', $user->id)->first();
        //if empty -> create
        //if !empty -> update

        if (!empty($input['full_name'])) {
            $names = explode(" ", trim($input['full_name']));
            $last = $names[0];
            unset($names[0]);
            $first = !empty($names) ? implode(" ", $names) : null;
        }
        if (empty($profile)) {
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'first_name' => $first,
                'avatar' => $partSaveProfile ?? null,
                'last_name' => $last,
                'phone' => $input['phone'],
                'email' => $input['email'],
            ];
            $profileModel->create($profileParam);
        } else {
            $profile->full_name =empty($input['full_name']) ? array_get($input, 'full_name', $profile->full_name) : $input['full_name'];
            $profile->birthday =empty($input['birthday']) ? array_get($input, 'birthday', $profile->birthday) : $input['birthday'];
            $profile->phone = empty($input['phone']) ? array_get($input, 'phone', $profile->phone) : $input['phone'];
            $profile->first_name = empty($first) ? array_get($input, 'first_name', $profile->first_name) : $first;
            $profile->last_name = empty($last) ? array_get($input, 'last_name', $profile->last_name) : $last;
            $profile->genre = empty($input['genre']) ? array_get($input, 'genre', $profile->genre) : $input['genre'];
            $profile->blood_group = empty($input['blood_group']) ? array_get($input, 'blood_group', $profile->blood_group) : $input['blood_group'];
            $profile->email = empty($input['email']) ? array_get($input, 'email', $profile->email) : $input['email'];
            $profile->address = empty($input['address']) ? array_get($input, 'address', $profile->address) : $input['address'];
            $profile->avatar = empty($partSaveProfile) ? array_get($input, 'avatar', $profile->avatar) : $partSaveProfile;
            $profile->updated_at = date("Y-m-d H:i:s", time());
            $profile->updated_by = OFFICE::getCurrentUserId();
            $profile->save();

            $education = Education::model()->where('user_id', $user->id)->first();
            if(!empty($input['degree']) || !empty($input['college_or_universiy'])){
                $educationModel = new Education();
                $educationParam = [
                    'user_id' => $user->id,
                    'degree' => $input['degree'],
                    'college_or_universiy' => $input['college_or_universiy'],
                    'year_of_completion' => empty($input['year_of_completion']) ? null : $input['year_of_completion'],
                    'is_active' => 1,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => OFFICE::getCurrentUserId(),
                ];
                $educationModel->create($educationParam);
            }
        }
        return $user;
    }
    /*------end ----------*/

    public function search($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['phone'])) {
            $query = $query->where('phone', 'like', "%{$input['phone']}%");
        }

        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (!empty($input['role_code'])) {
            $query->whereHas('role', function ($q) use ($input) {
                $q->where('code', $input['role_code']);
            });
        }

        if (!empty($input['role_code_not_equal'])) {
            $query->whereHas('role', function ($q) use ($input) {
                $q->where('code', '<>', $input['role_code_not_equal']);
            });
        }

        if (!empty($input['department_code'])) {
            $query->whereHas('department', function ($q) use ($input) {
                $q->where('code', $input['department_code']);
            });
        }

        if (!empty($input['full_name'])) {
            $query = $query->whereHas('profile', function ($q) use ($input) {
                $q->where('full_name', 'like', "%{$input['full_name']}%");
            });
        }
        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');
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

    public function fillter($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['phone'])) {
            $query = $query->where('phone', 'like', "%{$input['phone']}%");
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (!empty($input['role_code'])) {
            $query->whereHas('role', function ($q) use ($input) {
                $q->where('code', $input['role_code']);
            });
        }
        if (!empty($input['genre'])) {
            $query->whereHas('profile', function ($q) use ($input) {
                $q->where('genre', $input['genre']);
            });
        }
        if (!empty($input['department_code'])) {
            $query->whereHas('department', function ($q) use ($input) {
                $q->where('code', $input['department_code']);
            });
        }
        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');
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

    public function searchKDrive($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['phone'])) {
            $query = $query->where('phone', 'like', "%{$input['phone']}%");
        }
        if (!empty($input['full_name'])) {
            $query = $query->whereHas('profile', function ($q) use ($input) {
                $q->where('full_name', 'like', "%{$input['full_name']}%");
            });
        }
        if (!empty($input['department_id'])) {
            $query = $query->where('department_id', 'like', "%{$input['department_id']}%");
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        $query = $query->where('id', '!=', OFFICE::getCurrentUserId());
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
