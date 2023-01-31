<?php

namespace App\Exports;

use App\Models\Industrial;
use App\Models\Product\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProductCategoryExport implements FromView
{
    public function view(): View
    {
        // $list = ProductCategory::get();
        $list = ProductCategory::select('product_categories.*','users.name as users_name', 
            DB::raw('IF(7hill_product_categories.parent_id = 0, "Parent", 7hill_parent_category.name ) as parent_name '))
                                    ->join('users', 'users.id', '=', 'product_categories.added_by')
                                    ->leftJoin('product_categories as parent_category', 'parent_category.id', '=', 'product_categories.parent_id')->get();
        return view('platform.exports.product.product_category_excel', compact('list'));
    }
}
