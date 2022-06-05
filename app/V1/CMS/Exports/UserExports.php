<?php


namespace App\V1\CMS\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class UserExports implements FromQuery
{
    use Exportable;

    public function query(){
        $result = User::query()->select(
            'users.email as Email',
            'profiles.full_name as HoTen',
            'profiles.genre as GioiTinh',
            'profiles.birthday as Ngaysinh',
            'users.username as TaiKhoan',
        //'week.id',
        )
//->Where('health_record_books.updated_by', '>=', $from)
//            ->where('health_record_books.updated_by', '<=', $to)
            ->join('profiles', 'profiles.user_id', '=','users.id')
            ->join('health_record_books','health_record_books.user_id', '=', 'users.id');

        //print_r($result); die;
        return $result;


    }
}