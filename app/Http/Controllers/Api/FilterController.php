<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category\SubCategory;
use App\Models\Product\ProductCollection;
use App\Models\Product\Product;
use App\Models\Product\ProductAttributeSet;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductWithAttributeSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilterController extends Controller
{
    // public function getFilterStaticSideMenu()
    // {

    //     $product_availability = array( 
    //                                 'in_stock' => 'In Stock',
    //                                 'notify' => 'Upcoming',
    //                                 'exclude_out_of_stock' => 'Exclude Out Of Stock'
    //                             );

    //     // $video_shopping         = array( 'video_shopping' => 'Video Shopping is available');

    //     $sory_by                = array(
    //                                 array( 'id' => null, 'name' => 'Featured', 'slug' => 'is_featured' ),
    //                                 array( 'id' => null, 'name' => 'Price: High to Low', 'slug' => 'price_high_to_low' ),
    //                                 array( 'id' => null, 'name' => 'Price: High to Low', 'slug' => 'price_high_to_low' ),
    //                             );
    //     $tags                   = SubCategory::select('sub_categories.id', 'sub_categories.name', 'sub_categories.slug')
    //                                 ->join('main_categories', 'main_categories.id', '=', 'sub_categories.parent_id')
    //                                 ->where('sub_categories.status', 'published')
    //                                 ->where('main_categories.slug', 'product-tags')
    //                                 ->orderBy('sub_categories.order_by', 'asc')
    //                                 ->get()->toArray();

        
    //     $labels                   = SubCategory::select('sub_categories.id', 'sub_categories.name', 'sub_categories.slug')
    //                                 ->join('main_categories', 'main_categories.id', '=', 'sub_categories.parent_id')
    //                                 ->where('sub_categories.status', 'published')
    //                                 ->where('main_categories.slug', 'product-labels')
    //                                 ->orderBy('sub_categories.order_by', 'asc')
    //                                 ->get()->toArray();   
    //     $sory_by                = array_merge($tags, $labels, $sory_by);
        
    //     // $discounts              = ProductCollection::select('id', 'collection_name', 'slug')
    //     //                             ->where('can_map_discount', 'yes')
    //     //                             ->where('status', 'published')
    //     //                             ->orderBy('order_by', 'asc')
    //     //                             ->get()->toArray();
                                                   

    //     $response               = array(
    //                                 'product_availability' => $product_availability,
    //                                 // 'video_shopping' => $video_shopping,
    //                                 'sory_by' => $sory_by,
    //                                 // 'discounts' => $discounts
    //                             );

    //     return $response;
    
    // }
    public function getProducts(Request $request)
    {
        
        $page                   = $request->page ?? 0;
        $filter_category        = $request->category;
        $filter_availability    = $request->availability;
        $filter_attribute       = $request->attributes_category;
        $filter_availability_array = [];
        $filter_attribute_array = [];
        $filter_brand_array = [];
        if (isset($filter_attribute) && !empty($filter_attribute)) {
            
            $filter_attribute_array = explode("-", $filter_attribute);
        }
        if (isset($filter_availability) && !empty($filter_availability)) {
            $filter_availability_array = explode("-", $filter_availability);
        }
        if (isset($filter_brand) && !empty($filter_brand)) {
            $filter_brand_array     = explode("_", $filter_brand);
        }


        $productAttrNames = [];
        if( isset( $filter_attribute_array ) && !empty( $filter_attribute_array ) ) {
            $productWithData = ProductWithAttributeSet::whereIn('id', $filter_attribute_array)->get();
            if( isset( $productWithData ) && !empty( $productWithData ) ) {
                foreach ( $productWithData as $attr ) {
                    $productAttrNames[] = $attr->attribute_values;
                }
            }
        }
        $limit = 6;
        $skip = (isset($page) && !empty($page)) ? ($page * $limit) : 0;

        $from   = 1 + ($page * $limit);
        $to     = $skip + $limit;

        $take_limit = $limit + ($page * $limit);
        $total = Product::select('products.*')->where('products.status', 'published')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('product_categories as parent', 'parent.id', '=', 'product_categories.parent_id')
            ->when($filter_category != '', function ($q) use ($filter_category) {
                $q->where(function ($query) use ($filter_category) {
                    return $query->where('product_categories.slug', $filter_category);
                });
            })
            ->when($filter_availability != '', function ($q) use ($filter_availability_array) {
                return $q->whereIn('products.stock_status', $filter_availability_array);
            })
            ->when($filter_attribute != '', function ($q) use ($productAttrNames) {
                $q->join('product_with_attribute_sets', 'product_with_attribute_sets.product_id', '=', 'products.id');
                return $q->whereIn('product_with_attribute_sets.attribute_values', $productAttrNames);
            })        
            ->count();

        $details = Product::select('products.*')->where('products.status', 'published')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('product_categories as parent', 'parent.id', '=', 'product_categories.parent_id')
            ->when($filter_category != '', function ($q) use ($filter_category) {
                $q->where(function ($query) use ($filter_category) {
                    return $query->where('product_categories.slug', $filter_category);
                });
            })      
            ->when($filter_availability != '', function ($q) use ($filter_availability_array) {
                return $q->whereIn('products.stock_status', $filter_availability_array);
            })
            ->when($filter_attribute != '', function ($q) use ($productAttrNames) {
                $q->join('product_with_attribute_sets', 'product_with_attribute_sets.product_id', '=', 'products.id');
                return $q->whereIn('product_with_attribute_sets.attribute_values', $productAttrNames);
            })         
            ->groupBy('products.id')
            ->skip(0)->take($take_limit)
            ->get();
        $tmp = [];

        if (isset($details) && !empty($details)) {
            foreach ($details as $items) {

                $category               = $items->productCategory;

                $pro                    = [];
                $pro['id']              = $items->id;
                $pro['product_name']    = $items->product_name;
                $pro['category_name']   = $category->name ?? '';
                $pro['hsn_code']        = $items->hsn_code;
                $pro['product_url']     = $items->product_url;
                $pro['sku']             = $items->sku;
                $pro['stock_status']    = $items->stock_status;
                $pro['image']           = $items->base_image;
                $imagePath              = $items->base_image;

                if (!Storage::exists($imagePath)) {
                    $path               = asset('assets/logo/product-noimg.jpg');
                } else {
                    $url                = Storage::url($imagePath);
                    $path               = asset($url);
                }

                $pro['image']           = $path;

                $tmp[] = $pro;
            }
        }
        
        if ($total < $limit) {
            $to = $total;
        }

        return array('products' => $tmp, 'total_count' => $total, 'from' => ($total == 0 ? '0' : '1'), 'to' => $to);
    }
    public function getDynamicFilterCategory(Request $request)
    {
        $category_slug = $request->category_slug;
        // $category_slug = 'keyboard-keyboard';

        $productCategory = ProductCategory::where('slug', $category_slug)->first();
        if( isset( $productCategory ) && !empty( $productCategory ) ) {

            $whereIn = [];
            $whereIn[] = $productCategory->id;
            if( isset( $productCategory->childCategory ) && !empty( $productCategory->childCategory ) ) {
                foreach ( $productCategory->childCategory  as $items ) {
                    $whereIn[] = $items->id; 
                }
            }
            
            $data = [];
            // $attributeInfo = ProductAttributeSet::whereIn('product_category_id', $whereIn)->where('is_searchable', '1')->get();

            $filterData = ProductAttributeSet::select('product_attribute_sets.*')
                            ->join('product_categories', 'product_categories.id', '=', 'product_attribute_sets.product_category_id')
                            ->join('products', function($join){
                                $join->on('products.category_id', '=', 'product_categories.id');
                                $join->orOn('products.category_id', '=', 'product_categories.parent_id');
                            })
                            // ->join('product_with_attribute_sets', 'product_attribute_sets.id', '=', 'product_with_attribute_sets.product_attribute_set_id')
                            ->where('product_categories.slug', $category_slug )
                            // ->where('product_with_attribute_sets.status','published')
                            ->groupBy('product_attribute_sets.id')
                            ->get();
            
            
            if( isset( $filterData ) && !empty( $filterData ) ) {
                foreach ( $filterData as $item ) {
                    $tmp = [];
                    $tmp['filter_title'] = $item->title;
                    $tmp['filter_slug'] = $item->slug;
                    $tmp['filter_id'] = $item->id;
                    $tmp['child'] = $item->attributesFieldsByTitle ?? [];
                    $data[] = $tmp;
                                    
                }
                
            }
            return $data;

            
        }
    }
    public function getProductBySlug(Request $request)
    {
        $product_url = $request->product_url;
        $items = Product::where('product_url', $product_url)->first();

        $category               = $items->productCategory;

        $pro                    = [];
        $pro['id']              = $items->id;
        $pro['product_name']    = $items->product_name;
        $pro['category_name']   = $category->name ?? '';
        $pro['category_slug']   = $category->slug ?? '';
        $pro['parent_category_name']   = $category->parent->name ?? '';
        $pro['parent_category_slug']   = $category->parent->slug ?? '';
        $pro['brand_name']      = $items->productBrand->brand_name ?? '';
        $pro['hsn_code']        = $items->hsn_code;
        $pro['product_url']     = $items->product_url;
        $pro['sku']             = $items->sku;
        $pro['has_video_shopping'] = $items->has_video_shopping;
        $pro['stock_status']    = $items->stock_status;
        $pro['is_featured']     = $items->is_featured;
        $pro['is_best_selling'] = $items->is_best_selling;
        $pro['is_new']          = $items->is_new;
        // $pro['sale_prices']     = $salePrices;
        $pro['mrp_price']       = $items->price;
        $pro['videolinks']      = $items->productVideoLinks;
        $pro['links']           = $items->productLinks;
        $pro['image']           = $items->base_image;
        $pro['max_quantity']    = $items->quantity;


        $imagePath              = $items->base_image;

        if (!Storage::exists($imagePath)) {
            $path               = asset('userImage/no_Image.jpg');
        } else {
            $url                = Storage::url($imagePath);
            $path               = asset($url);
        }

        $pro['image']                   = $path;

        $pro['description']             = $items->description;
        $pro['technical_information']   = $items->technical_information;
        $pro['feature_information']     = $items->feature_information;
        $pro['specification']           = $items->specification;
        $pro['brochure_upload']         = $items->brochure_upload;
        // $pro['gallery']                 = $items->productImages;
        
        if (isset($items->productImages) && !empty($items->productImages)) {
            foreach ($items->productImages as $att) {

                $gallery_url            = Storage::url($att->gallery_path);
                $path                   = asset($gallery_url);

                $pro['gallery'][] = $path;
            }
            
        }

        $productWithAttributeSetData = ProductWithAttributeSet::where('product_id',$items->id)->groupBy('product_attribute_set_id')->select('product_attribute_set_id')->get();
       
        foreach($productWithAttributeSetData as $key=>$val)
        {
            $attributeData = ProductAttributeSet::where('id',$val['product_attribute_set_id'])->select('id','title','slug')->first();
                $parentData = []; 

                if( isset( $attributeData ) && !empty( $attributeData ) ) {
                    $dataVal = ProductWithAttributeSet::where('product_id',$items->id)->where('product_attribute_set_id',$attributeData->id)
                    ->select('attribute_values')->get();
                    // dd($dataVal);
                    $attrData['id'] = $attributeData->id;
                    $attrData['title'] = $attributeData->title;
                    $attrData['slug'] = $attributeData->slug;
                    $attrData['items'] = $dataVal;
        
                    $pro['attributes'][] =   $attrData;

                    // foreach( $attributeData as $items  ){
                    //     $parentData[] = $items;
                    // }
                }

        }
        // dd( $pro['attributes'] );
        ///////////////////////////

        // foreach($productWithAttributeSetData as $key=>$val)
        // {
        //     $attributeData = ProductAttributeSet::where('id',$val['product_attribute_set_id'])->select('id','title','slug')->first();
        //     $parentData = [];
        //     // print_r($attributeData)."<br>";
        //     if( isset( $attributeData->attributesFieldsByTitle ) && !empty( $attributeData->attributesFieldsByTitle ) ) {
        //         foreach( $attributeData->attributesFieldsByTitle as $items ){
        //             $parentData[] = $items;
        //         }
        //     }
        //     $attrData['id'] = $attributeData->id;
        //     $attrData['title'] = $attributeData->title;
        //     $attrData['slug'] = $attributeData->slug;
        //     $attrData['items'] = $parentData;

        //     $pro['attributes'][] =   $attrData;
            
        //     // $pro['attributes'][] =  $attributeData->productAttributes;
        //     // dd( $attributeData->productAttributes );
        // }
        // $pro['attributes']              = $items->productAttributes;

        ///////////////////////////
        $related_arr                    = [];
        
        $pro['meta'] = $items->productMeta;

        return $pro;
    }
    public function getOtherCategories(Request $request)
    {
        $category       = $request->category;
        $otherCategory   = ProductCategory::select('id', 'name', 'slug')
                        ->when($category != '', function ($q) use ($category) {
                            $q->where('slug', '!=', $category);
                        })
                        ->where(['status' => 'published'])
                        ->orderBy('order_by', 'asc')
                        ->get();
        $data = [];
        if( isset( $otherCategory ) && !empty( $otherCategory ) ) {
            foreach ($otherCategory as $item) {

                $tmp = [];
                $tmp['id'] = $item->id;
                $tmp['name'] = $item->name;
                $tmp['slug'] = $item->slug;
                $tmp['description'] = $item->description;

                $imagePath              = $item->image;
    
                if (!Storage::exists($imagePath)) {
                    $path               = asset('assets/logo/no-img-1.jpg');
                } else {
                    $url                = Storage::url($imagePath);
                    $path               = asset($url);
                }

                $tmp['image'] = $path;

                $data[] = $tmp;

            }
        } 
        return $data;
        
    }

}
