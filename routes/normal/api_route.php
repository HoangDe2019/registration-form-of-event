<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// Normal Group
//$api->version('v1', ['middleware' => ['cors']], function ($api) {
$api->version('v1', ['middleware' => ['cors2']], function ($api) {
    $api->group(['prefix' => 'v2', 'namespace' => 'App\V1\CMS\Controllers'], function ($api) {
        //add options
        $api->options('/{any:.*}', function () {
            return response(['status' => 'success'])
                ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, Origin');
        });//end
        $api->get('/', function () {
            return ['api-status' => 'Normal API status: Ok!'];
        });

        $api->get('/img/{img}', function ($img) {
            $img = str_replace(",", "/", $img);
            if (!file_exists(public_path() . "/" . $img)) {
                echo "File not found! 404";
                die;
            }
            $extension = pathinfo($img, PATHINFO_EXTENSION);
            $fileName = pathinfo($img, PATHINFO_BASENAME);
            $fp = fopen(public_path() . "/" . $img, 'rb');
            switch( $extension ) {
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpeg":
                case "jpg": $ctype="image/jpeg"; break;
                default:
            }

            header('Content-type: ' . $ctype);
            header("Content-Length: " . filesize(public_path() . "/" . $img));
            fpassthru($fp);
        });

        /*
        $api->get('/img/{img}', function ($img) {
            $img = str_replace(",", "/", $img);
            public_path() . "/" . $img;
            $extension = pathinfo($img, PATHINFO_EXTENSION);
            $fileName = pathinfo($img, PATHINFO_BASENAME);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {

                $fp = fopen(public_path() . "/" . $img, 'rb');

                header("Content-Type: image/png, image/jpeg");
                header("Content-Length: " . filesize(public_path() . "/" . $img));
                fpassthru($fp);
            }
            if ($extension == 'pdf') {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                @readfile($file);
                // doawload file
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                @readfile($file);
            }
            if ($extension == 'docx') {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile($file);
            }
            if ($extension == 'xlsx' || $extension == "pptx" || $extension == "ppt") {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'txt') {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'doc') {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-Type: application/msword');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'xls') {
                $filePdf = public_path() . "/" . $img;
                $file = $filePdf;
                $filename = $fileName;
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'zip') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'rar') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/x-rar-compressed, application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'rar5') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/x-rar5-compressed, application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'mp3') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: audio/mpeg');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'aac') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: audio/aac');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'avi') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: video/x-msvideo');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'bin') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'bmp') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: image/bmp');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'csv') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: text/csv');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'gif') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: image/gif');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'ico') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: image/vnd.microsoft.icon');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'jar') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/java-archive');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'jar') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/java-archive');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'json') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/json');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'mid' || $extension == 'midi') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: audio/midi, audio/x-midi');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'mpeg') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: video/mpeg');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'rtf') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/rtf');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'tar') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/x-tar');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'ttf') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: font/ttf');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'wav') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: audio/wav');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'weba') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: audio/weba');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == 'webm') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: video/webm');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == '3gp') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: video/3gpp, audio/3gpp');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            if ($extension == '7z') {
                $fileZip = public_path() . "/" . $img;
                $file = $fileZip;
                $filename = $fileName;
                header('Content-Type: application/x-7z-compressed');
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($file));
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                readfile("$file");
            }
            exit;
        });
        */

        // Setting view usually
//        require __DIR__ . '/demo.php';

        require __DIR__ . '/user.php';
        require __DIR__ . '/medical_schedule.php';
        require __DIR__ . '/TimeOfDay.php';
        require __DIR__ . '/department.php';
    });
});

