<?php


namespace App\V1\CMS\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class Prescidents implements FromQuery
{
    use Exportable;

    public function query(){

        return User::query()->select(
            'users.password',
            'profiles.full_name',
            'medical_schedules.checkin',
            'medical_schedules.session',
//                'users.password',
                'week.id',
        )->join('profiles', 'profiles.user_id', '=','users.id')
            ->join('medical_schedules','medical_schedules.user_id', '=', 'users.id')
            ->join('week', 'week.id', '=', 'medical_schedules.week_id');


    }
}