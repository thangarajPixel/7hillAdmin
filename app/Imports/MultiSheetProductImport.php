<?php

namespace App\Imports;

use App\Models\Industrial;
use App\Models\Master\Brands;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductLink;
use App\Models\Settings\Tax;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class MultiSheetProductImport implements ToModel, WithHeadingRow
{
   
    public function model(array $row)
    {
       
$status = (isset($row['status']) && strtolower($row['status']) == 'active') ? 'published' : 'unpublished';
        $ins = $cat_ins = $tax_ins = $subcat_ins = $brand_ins = $link_ins = [];
        $industrial                 = $row['industrial'] ?? null;
        $industrial_category        = $row['sub_industrial'] ?? null;
        $category           = $row['category'] ?? null;
        $sub_category       = $row['sub_category'] ?? null;
        if(  isset($industrial) && !empty($industrial) ) {
           
            $checkIndustrial = Industrial::where('title', trim($industrial) )->first();
            if( isset( $checkIndustrial ) && !empty( $checkIndustrial ) ) {
                $industrial_id                = $checkIndustrial->id;
                $categoryAppendSlug           = $checkIndustrial->slug;
            } else {
                $ind_ins['title']           = $industrial;
                $ind_ins['parent_id']       = 0;
                $ind_ins['description']     = $row['industrial_description'] ?? null;
                $ind_ins['status']          = 'published';
                $ind_ins['added_by']        = Auth::id();                
                $ind_ins['slug']            = Str::slug($industrial);
                $industrial_id                = Industrial::create($ind_ins)->id;

                $categoryAppendSlug =  $ind_ins['slug'];
            }
            
            $checkSubIndustrial = Industrial::where(['title' => trim($industrial_category), 'parent_id' => $industrial_id] )->first();
            if(isset( $industrial_category ) && !empty( $industrial_category ))
            {
                if( isset( $checkSubIndustrial ) && !empty( $checkSubIndustrial ) ) {
                    $sub_industrial_id                = $checkSubIndustrial->id;
                    $categoryAppendSlug               =  $checkSubIndustrial->slug;
                } else {
                    #insert new sub category
                    $subind_ins['added_by']         = Auth::id();
                    $subind_ins['title']            = trim($industrial_category);
                    $subind_ins['order_by']         = 0;
                    $subind_ins['status']           = 'published';
                    $subind_ins['parent_id']        = $industrial_id;

                    if( isset( $industrial_id ) && !empty( $industrial_id ) ) {
                        $parentInfo                 = Industrial::find($industrial_id);
                        $parent_name                = $parentInfo->name ?? '';
                    }
                    $subind_ins['slug']             = Str::slug($industrial_category);
                    $sub_industrial_id              = Industrial::create($subind_ins);

                    $categoryAppendSlug =  $subind_ins['slug'];
                } 
            }
            #do insert or update if data exist or not
            $checkCategory = ProductCategory::where('name', trim($category) )->first();
            
            if( isset( $checkCategory ) && !empty( $checkCategory ) ) {
                $category_id                = $checkCategory->id;
            } else {
                #insert new category                
                $cat_ins['name']            = $category;
                $cat_ins['parent_id']       = 0;
                if(isset($sub_industrial_id->id) && !empty($sub_industrial_id->id))
                {
                    $cat_ins['industrial_id']   = $sub_industrial_id->id;
                }
                else{
                    $cat_ins['industrial_id']   = $industrial_id;
                }
                $cat_ins['description']     = $row['category_description'] ?? null;
                $cat_ins['status']          = 'published';
                $cat_ins['added_by']        = Auth::id();                
                // $cat_ins['tax_id']       = $tax_id;
                $cat_ins['slug']            = Str::slug($category.' '.$categoryAppendSlug);
                $category_id                = ProductCategory::create($cat_ins)->id;

            }
            #check subcategory exist or create new one
            $checkSubCategory = ProductCategory::where(['name' => trim($sub_category), 'parent_id' => $category_id] )->first();
            if(isset( $sub_category ) && !empty( $sub_category ))
            {
                if( isset( $checkSubCategory ) && !empty( $checkSubCategory ) ) {
                    $sub_category_id                = $checkSubCategory->id;
                } else {
                    #insert new sub category
                    $subcat_ins['added_by']         = Auth::id();
                    $subcat_ins['name']             = trim($sub_category);
                    $subcat_ins['order_by']         = 0;
                    $subcat_ins['status']           = 'published';
                    $subcat_ins['parent_id']        = $category_id;
                    $subcat_ins['industrial_id']    = $sub_industrial_id->id;
                    $parent_name = '';
                    if( isset( $category_id ) && !empty( $category_id ) ) {
                        $parentInfo                 = ProductCategory::find($category_id);
                        $parent_name                = $parentInfo->name ?? '';
                    }
        
                    $subcat_ins['slug']             = Str::slug($sub_category.' '.$categoryAppendSlug);
                    $sub_category_id                = ProductCategory::create($subcat_ins)->id;
                }
            }
            if(!empty($category_id))
            {
                $catId = $category_id;
            }
            if(!empty($sub_category_id))
            {
                $catId = $sub_category_id;
            }
            $sku            = generateProductSku($row['sku']);
          
            $ins['product_name'] = trim($row['product_name']);
            $ins['product_url'] = Str::slug($row['product_name']);
            $ins['sku'] = $sku;
            $ins['status'] = 'published';
            $ins['quantity'] = '';
            $ins['stock_status'] = 'in_stock';
            $ins['industrial_id'] = $industrial_id ?? '';
            $ins['category_id'] = $catId ?? '';
            $ins['description'] = $row['short_description'];
            $ins['specification'] = $row['technical_specifications'];
            $ins['product_model'] = $row['product_model'];
            $ins['added_by'] = Auth::id();

            $product_id     = Product::create($ins)->id;  /////// This is important for save product
           
            
        }
        
       
    }
}
