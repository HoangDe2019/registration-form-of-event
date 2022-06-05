<?php

/**
 * User: Sy Dai
 * Date: 24-Mar-17
 * Time: 23:49
 */

namespace App\Http\Controllers\Auth;

use App\City;
use App\Http\Validators\CMSLoginValidator;
use App\OFFICE;
use App\Http\Controllers\Controller;
use App\Http\Validators\RegisterValidator;
use App\Http\Validators\LoginValidator;
use App\Profile;
use App\Role;
use App\Supports\Log;
use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\User;
use App\UserLog;
use App\UserSession;
use App\V1\CMS\Models\ProfileModel;
use App\V1\CMS\Models\UserModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    protected static $_user_type_user;
    protected static $_user_expired_day;
    /**
     *
     */
    protected $jwt;

    protected $model;

    /**
     * AuthController constructor.
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        self::$_user_type_user = "USER";
        self::$_user_expired_day = 365;
        $this->model = new UserModel();
    }

    /**
     * @param Request $request
     * @param LoginValidator $loginValidator
     *
     * @return mixed
     * @throws \Exception
     */
    public function authenticate(Request $request, CMSLoginValidator $loginValidator)
    {
        $input = $request->all();

        $loginValidator->validate($input);

        $credentials = $request->only('username', 'password');

        try {
            $token = $this->jwt->attempt($credentials);

            if (!$token) {
                return response()->json(['errors' => [[Message::get("users.admin-login-invalid")]]], 401);
            }

            $user = User::where(['username' => $input['username']])->first();

            if (empty($user)) {
                return response()->json(['errors' => [[Message::get("users.admin-login-invalid")]]], 401);
            }

            if ($user->is_active == "0") {
                return response()->json(['errors' => [[Message::get("users.user-inactive")]]], 401);
            }

            //Delete all records when they are 50 rows
            $user_coutnt = UserSession::model()->count();
            if($user_coutnt >= 50) {
                UserSession::truncate();
            }

            // Write User Session
            $now = time();
            UserSession::where('user_id', $user->id)->update([
                'deleted' => 1,
                'updated_at' => date("Y-m-d H:i:s", $now),
                'updated_by' => $user->user_id,
            ]);
            UserSession::where('user_id', $user->id)->delete();

            $device_type = array_get($input, 'device_type', 'UNKNOWN');
            //$device_type = array_get($input, 'device_type', $this->get_device()); //get devices login
            UserSession::insert([
                'user_id' => $user->id,
                'token' => $token,
                'login_at' => date("Y-m-d H:i:s", $now),
                'expired_at' => date("Y-m-d", strtotime("+1 day")),
                //'expired_at' => date("Y-m-d H:i:s", ($now + config('jwt.ttl') * 60)),
                'device_type' => $device_type,
                'device_id' => $_SERVER['REMOTE_ADDR'],
                'deleted' => 0,
                'created_at' => date("Y-m-d H:i:s", $now),
                'created_by' => $user->id,
            ]);

            $userCheck = User::where(['username' => $input['username']])->first();
            $profile = Profile::where(['user_id' => $userCheck['id']])->first();
            $rolename = Role::where(['id' => $userCheck['role_id']])->first();
            $avatar = !empty($profile['avatar']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $profile['avatar']) : null;
            //Record Log
            $user_logs_coutnt = UserLog::model()->count();
            if($user_logs_coutnt >= 200) {
                UserLog::truncate();
            }
            Log::login($this->model->getTable(), $userCheck['username']);

            $jsonReturn = [
                'token' => $token,
                'username' => $userCheck['username'],
                'full_name' => $profile['full_name'],
                'avatar' => $avatar,
                'role' => $rolename['name'],
                'role_code' => $rolename['code']
            ];
            /* print_r($jsonReturn); die;*/
        } catch (JWTException $e) {

            return response()->json(['errors' => [[$e->getMessage()]]], 500);
        } catch (\Exception $ex) {
            return response()->json(['errors' => [[$ex->getMessage()]]], 500);
        }

        // all good so return the token
        return response()->json($jsonReturn);
        //return [$token, $userCheck['username']];
    }

    /**
     * @param Request $request
     * @param RegisterValidator $registerValidator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRegister(Request $request, RegisterValidator $registerValidator)
    {
        $input = $request->all();

        $registerValidator->validate($input);

        if ($input['phone'] < 0 || strlen($input['phone']) < 9 || strlen($input['phone']) > 14) {
            return response()->json(['errors' => [[Message::get("V002", Message::get("phone"))]]], 500);
        }
        try {
            $phone = str_replace(" ", "", $input['phone']);
            $phone = preg_replace('/\D/', '', $phone);
            $param = [
                'phone' => $phone,
                'code' => $phone,
                'is_active' => 1,
            ];

            if (!empty($input['password'])) {
                $param['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
            }

            DB::beginTransaction();
            // Create User
            $user = $this->model->create($param);

            $names = explode(" ", trim($input['name']));
            $first = $names[0];
            unset($names[0]);
            $last = !empty($names) ? implode(" ", $names) : null;

            $prProfile = [
                'is_active' => 1,
                'first_name' => $first,
                'last_name' => $last,
                'short_name' => $input['name'],
                'full_name' => $input['name'],
                'genre' => array_get($input, 'genre', 'O'),
                'address' => array_get($input, 'address', null),
                'phone' => $input['phone'],
                'user_id' => $user->id,
            ];

            // Create Profile
            $profileModel = new ProfileModel();
            $profileModel->create($prProfile);

            DB::commit();

            return response()->json(['status' => Message::get("users.register-success", $input['phone'])], 200);
        } catch (QueryException $ex) {
            $response = OFFICE_Error::handle($ex);
            return response()->json(['errors' => [[$response['message']]]], 401);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return response()->json(['errors' => [[$response['message']]]], 401);
        }
    }

    public function logout()
    {
        try {

            $token = $this->jwt->getToken();

            $userId = OFFICE::getCurrentUserId();
            if (empty($userId)) {
                return response()->json([
                    'message' => Message::get('unauthorized'),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            UserSession::where('user_id', $userId)->where('deleted', '0')->update([
                'deleted' => 1,
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => $userId,
            ]);
            $this->jwt->invalidate($token);
        } catch (TokenInvalidException $exInvalid) {
            return response()->json([
                'message' => 'A token is invalid',
                'status_code' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        } catch (TokenExpiredException $exExpire) {
            return response()->json([
                'message' => 'A token is expired',
                'status_code' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        } catch (JWTException $jwtEx) {
            return response()->json([
                'message' => Message::get('logout-success'),
                'status_code' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => Message::get('logout-success'),
            'status_code' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function getValidAddress($countryId = 0, $cityId = 0, $districtId = 0, $wardId = 0)
    {
        $validCountryId = $validCityId = $validDistrictId = $validWardId = 0;
        if (empty($wardId)) {
            if (empty($districtId)) {
                if (empty($cityId)) {
                    if (empty($countryId)) {
                        return [];
                    } else {
                        $country = Country::find($countryId);
                        if (empty($country)) {
                            return [];
                        }
                        $validCountryId = $countryId;
                    }
                } else {
                    $city = City::find($cityId);
                    if (empty($city)) {
                        return [];
                    }

                    $validCityId = $cityId;
                    $validCountryId = $this->getParentLocationId('country_id', $cityId, City::class);
                }
            } else {
                $district = District::find($districtId);
                if (empty($district)) {
                    return [];
                }
                $validDistrictId = $districtId;
                $validCityId = $this->getParentLocationId('city_id', $districtId, District::class);
                $validCountryId = $this->getParentLocationId('country_id', $validCityId, City::class);
            }
        } else {
            $validWardId = $wardId;
            $validDistrictId = $this->getParentLocationId('district_id', $wardId, Ward::class);
            $validCityId = $this->getParentLocationId('city_id', $validDistrictId, District::class);
            $validCountryId = $this->getParentLocationId('country_id', $validCityId, City::class);
        }

        if (empty($validCountryId)) {
            return [];
        }

        return array_filter([
            'country_id' => $validCountryId,
            'city_id' => $validCityId,
            'district_id' => $validDistrictId,
            'ward_id' => $validWardId,
        ]);
    }

    private function getParentLocationId($parentColumn, $childLocationId, $childModel)
    {
        $child = $childModel::find($childLocationId);
        if (empty($child)) {
            return 0;
        }
        return $child->{$parentColumn};
    }
}