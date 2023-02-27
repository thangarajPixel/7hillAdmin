<?php

namespace App\Imports;

use App\Models\Industrial;
use App\Models\Product\Product;
use App\Models\Product\ProductAttributeSet;
use App\Models\Product\ProductWithAttributeSet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class UploadAttributes implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $sku = $row['sku'];
        if( isset( $sku ) && !empty( $sku ) ) {
            $product_info = Product::select('id', 'category_id')->where('sku', $row['sku'])->first();
            
            $category_id = $product_info->productCategory->id;

            if( $product_info->productCategory->parent_id == 0) {
                $attribute_set_name = $row['filter_variations'];
                $ins = [];
                $attr_slug = Str::slug($attribute_set_name);
                $attr_slug = $attr_slug.'-'.$product_info->productCategory->slug;
                $ins['title'] = $attribute_set_name;
                $ins['slug'] = $attr_slug;
                $ins['product_category_id'] = $category_id;
                $ins['status'] = 'published';

                ProductAttributeSet::updateOrCreate(['slug' => $attr_slug], $ins);

                $attribute_info = ProductAttributeSet::where('slug', $attr_slug)->first();
                if( !empty( $row['values'] ) ) {
                    $ins_set = [];
                    $ins_set['product_id'] = $product_info->id;
                    $ins_set['product_attribute_set_id'] = $attribute_info->id;
                    $ins_set['attribute_values'] = $row['values'];
                    $ins_set['status'] = 'published';
                    ProductWithAttributeSet::updateOrCreate(['product_id' => $product_info->id, 'product_attribute_set_id' => $attribute_info->id ], $ins_set);
                }

            }
            
           
        }
    }
}