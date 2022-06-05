<?php

namespace App\Http\Middleware;

use App\Supports\JWTUtil;
use App\Supports\Message;
use App\Supports\OFFICE_Email;
use App\Supports\OFFICE_SMS;
use Closure;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;
use Namshi\JOSE\JWS;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Support\Utils;
use Illuminate\Http\Response;

class VerifySecret
{

    /**
     * @param         $request
     * @param Closure $next
     *
     * @return mixed
     * @throws JWTException
     * @throws TokenInvalidException
     */
    public function handle($request, Closure $next)
    {
        try {
            $headers = $request->headers->all();

            if (empty($headers['authorization'])) {
                return response()->json([
                    'message' => Message::get("V001", "Token"),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'message' => Message::get('unauthorized'),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            $jws = JWS::load($token);
        } catch (Exception $e) {
            return response()->json([
                'message' => Message::get("unknown", $e->getMessage()),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\InvalidArgumentException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        } catch (TokenBlacklistedException $blacklistedException) {
            return response()->json([
                'message' => $blacklistedException->getMessage(),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$jws->verify(config('jwt.secret'), config('jwt.algo'))) {
            return response()->json([
                'message' => Message::get("token_invalid"),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (Utils::timestamp(JWTUtil::getPayloadValue('exp'))->isPast()) {
            return response()->json([
                'message' => Message::get("token_expired"),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userSession = DB::table('user_sessions')->where('token', $token)->first();
        if (empty($userSession)) {
            return response()->json([
                'message' => Message::get("token_invalid"),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (strtotime($userSession->expired_at) < time()) {
            return response()->json([
                'message' => Message::get("token_expired"),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($userSession->deleted != 0) {
            $userId = DB::table('users')->where('id', $userSession->user_id)->first();
            $profile = DB::table('profiles')->where('user_id', $userSession->user_id)->first();
            $date_login = date('d-m-Y H:i:s', strtotime($userSession->deleted_at));
            $paramSendMail = [
                'content' => $userId->username,
                'full_name' => $profile->full_name,
                'login_in_at' => $date_login
            ];
            OFFICE_Email::send('report_issue_user_login_other', $userId->email, $paramSendMail);
            $content = "Tài khoản {$userId->username} của bạn hiện tại đã được đăng nhập ở trên thiết bị khác vào lúc {$date_login}";
            //OFFICE_SMS::sendSMS($userId->phone, $content);
            return response()->json([
                'message' => Message::get("login_other"),
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
