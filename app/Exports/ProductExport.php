<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Product\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    public function view(): View
    {

        $f_product_category = request()->filter_product_category;
        $f_stock_status = request()->filter_stock_status;
        $f_product_name = request()->filter_product_name;
        $f_product_status = request()->filter_product_status;

        $list = Product::when($f_product_category, function($q) use($f_product_category){
                            return $q->where('category_id', $f_product_category);
                        })
                        ->when($f_stock_status, function($q) use($f_stock_status) {
                            return $q->where('stock_status', $f_stock_status);
                        })
                        ->when($f_product_status, function($q) use($f_product_status) {
                            return $q->where('status', $f_product_status);
                        })
                        ->when($f_product_name, function($q) use($f_product_name) {
                            return $q->where('product_name', 'like', "%{$f_product_name}%")->orWhere('sku', 'like', "%{$f_product_name}%")->orWhere('price', 'like', "%{$f_product_name}%");
                        })
                        ->get();

        return view('platform.exports.product.products_excel', compact('list'));
    }
}
