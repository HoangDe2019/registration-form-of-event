<?php

namespace App;

use App\Supports\Message;
use App\V1\CMS\Models\PermissionModel;
use App\V1\CMS\Models\RolePermissionModel;
use Dingo\Api\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OFFICE
{
    private static $_data;

    public static final function info()
    {
        self::__getUserInfo();

        return self::$_data;
    }

    private static final function __getUserInfo()
    {

        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json([
                'message' => 'A token is required',
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)) {
                return response()->json([
                    'message' => Message::get("V003", Message::get('customers')),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (TokenExpiredException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        } catch (TokenBlacklistedException $blacklistedException) {
            return response()->json([
                'message' => $blacklistedException,
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }
        $permissionModel = new PermissionModel();
        $rolePermissionModel = new RolePermissionModel();
        $roles = RolePermission::model()
            ->select([
                $permissionModel->getTable() . '.name as permission_name',
                $permissionModel->getTable() . '.code as permission_code'
            ])
            ->where('role_id', $user->role_id)
            ->join($permissionModel->getTable(), $permissionModel->getTable() . '.id', '=',
                $rolePermissionModel->getTable() . '.permission_id')
            ->get()->toArray();
        $permissions = array_pluck($roles, "permission_name", 'permission_code');

        self::$_data = [
            'id' => $user->id,
            'email' => $user->email,
            'user' => $user->user,
            'company_id' => object_get($user, 'company_id', null),
            'first_name' => object_get($user, 'profile.first_name', null),
            'last_name' => object_get($user, 'profile.last_name', null),
            'full_name' => object_get($user, 'profile.full_name', null),
            'is_super' => $user->is_super,
            'role_code' => object_get($user, 'role.code', null),
            'role_name' => object_get($user, 'role.name', null),
            'price_show' => $user->price_show,
            'permissions' => $permissions,
        ];
    }

    //------------------static function-------------------------------

    public static final function isSuper()
    {
        $userInfo = self::info();

        return $userInfo['is_super'] == 1 ? true : false;
    }

    public static final function isAdminUser()
    {
        $userInfo = self::info();

        return !empty($userInfo['role_code']) && $userInfo['role_code'] == "ADMIN" ? true : false;
    }

    public static final function getCurrentUserId()
    {
        $userInfo = self::info();

        return $userInfo['id'] ?? null;
    }

    public static final function getCurrentUserName()
    {
        $userInfo = self::info();

        return $userInfo['full_name'] ?? null;
    }

    public static final function getCurrentCompanyId()
    {
        $companyInfo = self::info();

        return $companyInfo['company_id'] ?? null;
    }

    public static final function getUpdatedBy()
    {
        $userInfo = self::info();

        return "USER: #" . $userInfo['id'];
    }

    public static final function isPriceShow()
    {
        $userInfo = self::info();

        return $userInfo['price_show'] === 1 ? true : false;
    }

    public static final function getCurrentPermission()
    {
        $userInfo = self::info();

        return $userInfo['permissions'];
    }

    public static final function getCurrentRoleCode()
    {
        $userInfo = self::info();

        return $userInfo['role_code'];
    }

    public static final function getCurrentRoleName()
    {
        $userInfo = self::info();

        return $userInfo['role_name'];
    }

    public static final function urlBase($url = null)
    {
        $base = env("APP_URL");
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $base = $_SERVER['HTTP_REFERER'];
        } elseif (!empty($_SERVER['HTTP_ORIGIN'])) {
            $base = $_SERVER['HTTP_ORIGIN'];
        }
        $base = $base . $url;
        $base = str_replace(" ", "", $base);
        $base = str_replace("\\", "/", $base);
        $base = str_replace("//", "/", $base);
        $base = str_replace(":/", "://", $base);

        return $base;
    }

    public static final function allowRemote()
    {
        $allowRemote = env("APP_ALLOW_REMOTE", 0);
        if ($allowRemote === 1 || $allowRemote === "1") {
            return true;
        }

        if (empty($_SERVER["REMOTE_ADDR"])) {
            return false;
        }
        if (empty($_SERVER['HTTP_ORIGIN'])) {
            return false;
        }

        $clientDomain = trim($_SERVER["HTTP_ORIGIN"]);
        $clientDomain = str_replace(" ", "", $clientDomain);
        $clientDomain = strtolower($clientDomain);
        $clientIp = $_SERVER["SERVER_ADDR"];

        $remote = DB::table('settings')->select(['id', 'name'])->where('code', 'REMOTE')->first();
        if (!empty($remote)) {
            return false;
        }

        $remote = json_decode($remote, true);
        if (!empty($remote) || !is_array($remote)) {
            return false;
        }

        foreach ($remote as $item) {
            if ($item["ip"] == $clientIp && $item["domain"] == $clientDomain) {
                return true;
            }
        }

        return false;
    }

    public static final function strToSlug($str)
    {
        // replace non letter or digits by -
        $str = preg_replace('~[^\pL\d]+~u', '-', $str);

        // transliterate
        $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);

        // remove unwanted characters
        $str = preg_replace('~[^-\w]+~', '', $str);

        // trim
        $str = trim($str, '-');

        // remove duplicate -
        $str = preg_replace('~-+~', '-', $str);

        // lowercase
        $str = strtolower($str);

        if (empty($str)) {
            return 'n-a';
        }

        return $str;
    }

    public static final function array_get($array, $key, $default = null)
    {
        if (!Arr::accessible($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (Arr::exists($array, $key)) {
            if ($array[$key] === "" || $array[$key] === null) {
                return $default;
            }
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (Arr::accessible($array) && Arr::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * @param string $date
     * @param string $format
     * @return false|string
     */
    public static final function dateFEtoBE(string $date, string $format = "d/m/Y H:i")
    {
        $date = trim($date);

        if (empty($date)) {
            return "";
        }
        $dateTime = explode(" ", $date);

        if (count($dateTime) != 2) {
            return " ";
        }

        $date = $dateTime[0];
        $time = $dateTime[1] . ":00";

        $days = explode("/", $date);
        if (count($days) != 3) {
            return "";
        }

        return date("Y-m-d H:i:s", strtotime("{$days[2]}-{$days[1]}-{$days[0]} $time"));
    }
}
