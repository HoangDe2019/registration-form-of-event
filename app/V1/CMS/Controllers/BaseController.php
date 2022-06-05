<?php
/**
 * User: daihs
 * Date: 01/09/2021
 * Time: 14:18
 */

namespace App\V1\CMS\Controllers;

use App\Supports\Message;
use Laravel\Lumen\Routing\Controller;
use Dingo\Api\Routing\Helpers;
use Maatwebsite\Excel\Facades\Excel;
class BaseController extends Controller
{
    use Helpers;

    public function ExcelExport($fileName, $dir, $data)
    {
        if (empty($data[0])) {
            throw new \Exception(Message::get("V002", "Export Data"));
        }
        $chars = config("constants.EXCEL.CHAR");
        $totalRow = count($data[0]);
        if (empty($chars[$totalRow])) {
            throw new \Exception(Message::get("V002", "Excel Alphabet Title"));
        }
        $maxChar = $chars[$totalRow];
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        Excel::download(function ($writer) use ($data, $maxChar) {
            $writer->sheet('Report', function ($sheet) use ($data, $maxChar) {
                $sheet->setAutoSize(true);
                $sheet->setHeight(1, 30);
                $countData = count($data);
                // Fill suggest Data
                $sheet->fromArray($data, null, 'A1', true, false);
                // Wrap Text
                $sheet->getStyle("{$maxChar}1:{$maxChar}$countData")->getAlignment()->setWrapText(true);
                $sheet->setBorder("A1:$maxChar$countData", 'thin');
                $sheet->cell("A1:{$maxChar}1", function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight();
                    $cell->setBackground("#3399ff");
                    $cell->setFontColor("#ffffff");
                });
                $sheet->cell("A1:$maxChar$countData", function ($cell) {
                    $cell->setValignment('center');
                });
                $columsAlign = [
//                    "A1:C$countData"                   => "left",
//                    "D1:J$countData"                   => "center",
//                    "L1:M$countData"                   => "center",
//                    "{$maxChar}1:{$maxChar}$countData" => "center",
                    "A1:{$maxChar}$countData" => "center",
                ];
                foreach ($columsAlign as $cols => $align) {
                    $sheet->cell($cols, function ($cell) use ($align) {
                        $cell->setAlignment($align);
                    });
                }
            });
        }, $fileName, $dir);

        $fileExported = $fileName . ".xlsx";
        header('Access-Control-Allow-Origin: *');
        readfile("$dir/$fileExported");
    }

    function convert_number_to_words($number)
    {
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = array(
            0 => 'Không',
            1 => 'Một',
            2 => 'Hai',
            3 => 'Ba',
            4 => 'Bốn',
            5 => 'Năm',
            6 => 'Sáu',
            7 => 'Bảy',
            8 => 'Tám',
            9 => 'Chín',
            10 => 'Mười',
            11 => 'Mười một',
            12 => 'Mười hai',
            13 => 'Mười ba',
            14 => 'Mười bốn',
            15 => 'Mười năm',
            16 => 'Mười sáu',
            17 => 'Mười bảy',
            18 => 'Mười tám',
            19 => 'Mười chín',
            20 => 'Hai mươi',
            30 => 'Ba mươi',
            40 => 'Bốn mươi',
            50 => 'Năm mươi',
            60 => 'Sáu mươi',
            70 => 'Bảy mươi',
            80 => 'Tám mươi',
            90 => 'Chín mươi',
            100 => 'trăm',
            1000 => 'ngàn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return false;
        }
        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }
}