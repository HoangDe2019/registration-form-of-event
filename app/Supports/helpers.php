<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }
}

if (!function_exists('public_path')) {
    /**
     * Return the path to public dir
     *
     * @param null $path
     *
     * @return string
     */
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if (!function_exists('get_image')) {
    /**
     * @param $url
     *
     * @return string
     */
    function get_image($url)
    {
        if (empty($url)) {
            return null;
        }

        $path = storage_path(Config::get('constants.URL_IMG')) . "/$url";

        $type = pathinfo($path, PATHINFO_EXTENSION);

        if (!file_exists($path)) {
            return null;
        }

        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

if (!function_exists('upload_image')) {
    /**
     * @param        $data
     * @param        $dir
     * @param        $file_name
     * @param string $type
     *
     * @return bool|null
     */
    function upload_image($data, $dir, $file_name, $type = "jpg")
    {
        if (empty($data)) {
            return null;
        }

        if (!file_exists(storage_path(Config::get('constants.URL_IMG')) . "/" . $dir)) {
            mkdir(storage_path(Config::get('constants.URL_IMG')) . "/" . $dir, 0777, true);
        }

        file_put_contents(storage_path(Config::get('constants.URL_IMG')) . "/$dir/$file_name.$type",
            base64_decode(preg_replace('#^data:image/\w+;base64,#i',
                '', $data)));

        return true;
    }
}

if (!function_exists('is_image')) {
    /**
     * @param $base64
     *
     * @return bool
     */
    function is_image($base64)
    {
        if (empty($base64)) {
            return false;
        }

        $base = base64_decode($base64);

        if (empty($base)) {
            return false;
        }

        $file_size = strlen($base);

        if ($file_size / 1024 / 1024 > Config::get("constant.IMG_UPLOAD_MAXSIZE", 1)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('array_get_mes')) {
    function array_get_ssc($array, $key, $default = null)
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

    if (!function_exists('get_device')) {
        function get_device()
        {
            $tablet_browser = 0;
            $mobile_browser = 0;

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i',
                strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $tablet_browser++;
            }

            if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i',
                strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $mobile_browser++;
            }

            if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),
                        'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))
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
                $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
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
}

if (!function_exists("string_to_slug")) {
    function string_to_slug($str)
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
}

if (!function_exists("getDatesBetween")) {
    function getDatesBetween($inputFrom, $inputTo)
    {
        $start = new \DateTime($inputFrom);
        $interval = new \DateInterval('P1D');
        $end = new \DateTime(date('Y-m-d', strtotime("+1 day", strtotime($inputTo))));

        $period = new \DatePeriod($start, $interval, $end);

        $dates = array_map(function ($d) {
            return $d->format("Y-m-d");
        }, iterator_to_array($period));

        return $dates;
    }
}