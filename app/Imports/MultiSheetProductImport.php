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
            } else {
                $ind_ins['title']           = $industrial;
                $ind_ins['parent_id']       = 0;
                $ind_ins['description']     = $row['industrial_description'] ?? null;
                $ind_ins['status']          = 'published';
                $ind_ins['added_by']        = Auth::id();                
                $ind_ins['slug']            = Str::slug($industrial);
                $industrial_id                = Industrial::create($ind_ins)->id;
            }
            
            $checkSubIndustrial = Industrial::where(['title' => trim($industrial_category), 'parent_id' => $industrial_id] )->first();
            if( isset( $industrial_category ) && !empty( $industrial_category ) ) {
                $sub_industrial_id                = $checkSubIndustrial->id;
            } else {
                #insert new sub category
                // $subcat_ins['tax_id']           = $tax_id;
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
            }
// print_r("industrial_id=".$industrial_id."<br>"."sub_industrial_id=".$sub_industrial_id);die();
            //////////////
            #do insert or update if data exist or not
            $checkCategory = ProductCategory::where('name', trim($category) )->first();
            
            if( isset( $checkCategory ) && !empty( $checkCategory ) ) {
                $category_id                = $checkCategory->id;
            } else {
                #insert new category                
                $cat_ins['name']            = $category;
                $cat_ins['parent_id']       = 0;
                $cat_ins['industrial_id']   = $industrial_id;
                $cat_ins['description']     = $row['category_description'] ?? null;
                $cat_ins['status']          = 'published';
                $cat_ins['added_by']        = Auth::id();                
                // $cat_ins['tax_id']       = $tax_id;
                $cat_ins['slug']            = Str::slug($category);
                $category_id                = ProductCategory::create($cat_ins)->id;

            }
            #check subcategory exist or create new one
            $checkSubCategory = ProductCategory::where(['name' => trim($sub_category), 'parent_id' => $category_id] )->first();
            if( isset( $checkSubCategory ) && !empty( $checkSubCategory ) ) {
                $sub_category_id                = $checkSubCategory->id;
            } else {
                #insert new sub category
                // $subcat_ins['tax_id']           = $tax_id;
                // $subcat_ins['is_home_menu']     = 'no';
                $subcat_ins['added_by']         = Auth::id();
                $subcat_ins['name']             = trim($sub_category);
                $subcat_ins['order_by']         = 0;
                $subcat_ins['status']           = 'published';
                $subcat_ins['parent_id']        = $category_id;

                $parent_name = '';
                if( isset( $category_id ) && !empty( $category_id ) ) {
                    $parentInfo                 = ProductCategory::find($category_id);
                    $parent_name                = $parentInfo->name ?? '';
                }
    
                $subcat_ins['slug']             = Str::slug($sub_category.' '.$parent_name);
                $sub_category_id                = ProductCategory::create($subcat_ins);

            }
            #check brand exist or create new one
            // $checkBrand                         = Brands::where('brand_name', trim($row['brand']))->first();
            // if( isset( $checkBrand ) && !empty( $checkBrand ) ) {
            //     $brand_id                       = $checkBrand->id;
            // } else {
            //     #insert new brand
            //     $brand_ins['brand_name']    = trim($row['brand']);
            //     $brand_ins['slug']          = Str::slug($row['brand']);
            //     $brand_ins['order_by']      = 0;
            //     $brand_ins['status']        = 'published';

            //     $brand_id                   = Brands::create($brand_ins)->id;
            // }

            #check product exist or create new one
            $sku            = generateProductSku($row['sku']);
            // $amount         = $row['base_price'] ?? $row['tax_inclexcl'] ?? 0;
            // $productPriceDetails = getAmountExclusiveTax((float)$amount, $taxPercentage ?? 0 );

            $ins['product_name'] = trim($row['product_name']);
            $ins['product_url'] = Str::slug($row['product_name']);
            $ins['sku'] = $sku;
            // $ins['price'] = $productPriceDetails['basePrice'] ?? 0;
            // $ins['mrp'] = $row['mrp'] ?? 0;
            // $ins['sale_price'] = $row['discounted_price'] ?? 0;
            // $ins['sale_start_date'] = ( isset($row['start_date']) && !empty( $row['start_date']) ) ? date('Y-m-d', strtotime($row['start_date'])) : null;
            // $ins['sale_end_date'] = ( isset($row['end_date']) && !empty( $row['end_date']) ) ? date('Y-m-d', strtotime($row['end_date'])) : null;
            $ins['status'] = 'published';
            $ins['quantity'] = '';
            $ins['stock_status'] = 'in_stock';
            // $ins['brand_id'] = $brand_id;
            $ins['category_id'] = $sub_category_id;
            // $ins['is_featured'] = ( isset($row['featured']) && !empty( $row['featured']) ) ? 1 : 0;
            // $ins['tax_id'] = $tax_id;
            $ins['description'] = $row['short_description'];
            $ins['specification'] = $row['technical_specifications'];
            $ins['product_model'] = $row['product_model'];
            // $ins['technical_information'] = $row['technical_specifications'] ?? null;
            // $ins['feature_information'] = $row['4_bullet_points'] ?? null;
            // $ins['specification'] = $row['long_description'] ?? null;
            $ins['added_by'] = Auth::id();

            $product_id     = Product::create($ins)->id;
            // dd("!11");

            // if( isset( $row['video_link']) && !empty( $row['video_link'])) {
            //     $link_ins['product_id'] = $product_id;
            //     $link_ins['url'] = $row['video_link'];
            //     $link_ins['url_type'] = 'video_link';
            //     ProductLink::create($link_ins);
            // }
            
        }
        
       
    }
}
