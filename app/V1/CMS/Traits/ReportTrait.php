<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 7/17/2019
 * Time: 10:10 PM
 */

namespace App\V1\CMS\Traits;


trait ReportTrait
{
    protected function writeExcelIssueUserReport($fileName, $dir, $data)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        \Excel::create($fileName, function ($writer) use ($data) {

            $writer->sheet('Report', function ($sheet) use ($data) {
                $sheet->loadView('report_issue_user', $data);
            });
        })->store('xlsx', $dir);

        $fileExported = $fileName . ".xlsx";
        header('Access-Control-Allow-Origin: *');
        readfile("$dir/$fileExported");
    }
}