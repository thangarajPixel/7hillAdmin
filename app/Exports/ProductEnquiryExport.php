<?php

namespace App\Exports;

use App\Models\ProductEnquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductEnquiryExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ProductEnquiry::all();
    // }
    public function view(): View
    {
        $list = ProductEnquiry::select('product_enquiries.*','products.product_name')
        ->leftJoin('products','products.id','=','product_enquiries.product_id')
        ->get();
        return view('platform.exports.enquiry.excel', compact('list'));
    }
}
