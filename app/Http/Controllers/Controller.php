<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     *
     * /*

    @OA\OpenApi(
    @OA\Server(
    url="http://localhost:8080",
    description="API server",
    ),
    @OA\Info(
    version="1.0.0",
    title="Swagger Lumen",
    description="API Swagger Lumen thể hiện tính năng của Swagger 3 - Lumen 5.6",
    termsOfService="http://swagger.io/terms/",
    @OA\Contact(name="Swagger API Team"),
    @OA\License(name="Huỳnh Mạnh Dần")
    ),
    ) */
    /**

    @OA\SecurityScheme(
    type="oauth2",
    name="petstore_auth",
    securityScheme="petstore_auth",
    @OA\Flow(
    flow="implicit",
    authorizationUrl="http://petstore.swagger.io/oauth/dialog",
    scopes={
    "write:pets": "modify pets in your account",
    "read:pets": "read your pets",
    }
    )
    )
    @OA\SecurityScheme(
    type="apiKey",
    in="header",
    securityScheme="api_key",
    name="api_key"
    ) */


    function get_device()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match(
            '/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i',
            strtolower($_SERVER['HTTP_USER_AGENT'])
        )) {
            $tablet_browser++;
        }

        if (preg_match(
            '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i',
            strtolower($_SERVER['HTTP_USER_AGENT'])
        )) {
            $mobile_browser++;
        }

        if ((strpos(
                strtolower($_SERVER['HTTP_ACCEPT']),
                'application/vnd.wap.xhtml+xml'
            ) > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))
        ) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = [
            'w3c ',
            'acs-',
            'alav',
            'alca',
            'amoi',
            'audi',
            'avan',
            'benq',
            'bird',
            'blac',
            'blaz',
            'brew',
            'cell',
            'cldc',
            'cmd-',
            'dang',
            'doco',
            'eric',
            'hipt',
            'inno',
            'ipaq',
            'java',
            'jigs',
            'kddi',
            'keji',
            'leno',
            'lg-c',
            'lg-d',
            'lg-g',
            'lge-',
            'maui',
            'maxo',
            'midp',
            'mits',
            'mmef',
            'mobi',
            'mot-',
            'moto',
            'mwbp',
            'nec-',
            'newt',
            'noki',
            'palm',
            'pana',
            'pant',
            'phil',
            'play',
            'port',
            'prox',
            'qwap',
            'sage',
            'sams',
            'sany',
            'sch-',
            'sec-',
            'send',
            'seri',
            'sgh-',
            'shar',
            'sie-',
            'siem',
            'smal',
            'smar',
            'sony',
            'sph-',
            'symb',
            't-mo',
            'teli',
            'tim-',
            'tosh',
            'tsm-',
            'upg1',
            'upsi',
            'vk-v',
            'voda',
            'wap-',
            'wapa',
            'wapi',
            'wapp',
            'wapr',
            'webc',
            'winw',
            'winw',
            'xda ',
            'xda-',
        ];

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser++;
            $stock_ua = sztrtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            return 'TABLET';
        } else if ($mobile_browser > 0) {
            return 'PHONE';
        } else {
            return 'DESKTOP';
        }
    }
}
