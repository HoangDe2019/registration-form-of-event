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

// Authorized Group
$api->version('v1', ['middleware' => ['cors2', 'trimInput', 'verifySecret', 'authorize']], function ($api) {
    $api->group(['prefix' => 'v1', 'namespace' => 'App\V1\CMS\Controllers'], function ($api) {
        $api->options('/{any:.*}', function () {
            return response(['status' => 'success'])
                ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, Origin');
        });

        $api->get('/', function () {
            return ['api-status' => 'TEST API Ok!'];
        });

        //get img
        $api->get('/img/{img}', function ($img) {
            $img = str_replace(",", "/", $img);
            if (!file_exists(public_path() . "/" . $img)) {
                echo "File not found!";
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

        // News
        require __DIR__ . '/news.php';

        // Users
        require __DIR__ . '/user.php';

        // Support
        require __DIR__ . '/support.php';

        // Info
        require __DIR__ . '/info.php';

        // Notify
        require __DIR__ . '/notify.php';

        // Import
        require __DIR__ . '/import.php';

        // Role
        require __DIR__ . '/role.php';

        // Permissions
        require __DIR__ . '/permission.php';

        // Permission Group
        require __DIR__ . '/permission_group.php';

        // Department
        require __DIR__ . '/department.php';

        // Issue
        require __DIR__ . '/issue.php';

        // Module Category
        require __DIR__ . '/module_category.php';

        // Discuss
        require __DIR__ . '/discuss.php';

        // Discuss
        require __DIR__ . '/module.php';

        // File
        require __DIR__ . '/file.php';

        // FOLDERS
        require __DIR__ . '/folders.php';

        // Report
        require __DIR__ . '/report.php';

        // User Log
        require __DIR__ . '/user_log.php';

        // Log Word
        require __DIR__ . '/log_word.php';

        // Static Drive
        require __DIR__ . '/static_drive.php';

        // Company
        require __DIR__ . '/company.php';

        //Week
        require __DIR__ . '/week.php';

        //analysis
        require __DIR__ . '/analysis.php';

        //Medicine_origin
        require __DIR__ . '/medicine_origin.php';

        //Medical_Schedules
        require __DIR__ . '/medical_schedule.php';

        //Health_record_book
        require __DIR__ . '/health_record_book.php';

        //book_before
        require __DIR__ . '/book_before.php';

        //diseases
        require __DIR__ . '/diseases.php';

        //medicine
        require __DIR__ . '/medicine.php';

        //medical_history
        require __DIR__ . '/medical_history.php';

        //precription and detail
        require __DIR__ . '/prescription.php';

        //benh chuan doan
        require __DIR__ . '/diseases_diagnosed.php';

        //kq xet nghiem
        require __DIR__ . '/test_result.php';

        //Time Of Day
        require __DIR__ . '/TimeOfDay.php';
    });
});

