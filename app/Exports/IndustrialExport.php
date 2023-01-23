<?php

namespace App\Exports;

use App\Models\Industrial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class IndustrialExport implements FromView
{
    public function view(): View
    {
        $list = Industrial::all();
        return view('platform.exports.industrial.excel', compact('list'));
    }
}
