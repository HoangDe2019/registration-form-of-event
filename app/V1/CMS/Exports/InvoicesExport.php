<?php


namespace App\V1\CMS\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\User;

class InvoicesExport implements FromView
{
    public function view(): View
    {
        return view('exports.users', [
            'users' => User::all()
        ]);
    }
}