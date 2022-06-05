<?php
/**
 * User: Sy Dai
 * Date: 30-Jun-18
 * Time: 22:55
 */

namespace App\Supports;

use App\Jobs\SendMailErrorJob;
use App\OFFICE;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class OFFICE_Error
{
    public static function handle(\Exception $ex)
    {
        $errorCode = $ex->getCode();
        $errorCode = empty($errorCode) ? Response::HTTP_BAD_REQUEST : $errorCode;

        if (env('APP_DEBUG', false) == true) {
            $request = Request::capture();
            $param = $request->all();
            $data = [
                'server' => OFFICE::urlBase(),
                'time' => date("Y-m-d H:i:s", time()),
                'user_id' => OFFICE::getCurrentUserId(),
                'param' => json_encode($param),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'error' => $ex->getMessage(),
            ];

            //Write Log

            // Send Mail
            dispatch(new SendMailErrorJob($data));
        }

        if (env('APP_ENV') == 'testing') {
            return ['message' => $ex->getMessage(), 'code' => $errorCode];
        } else {
            return ['message' => Message::get("R011"), 'code' => Response::HTTP_BAD_REQUEST];
        }
    }
}