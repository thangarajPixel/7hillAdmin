<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industrial;
use App\Models\Product\Product;
use App\Models\Product\ProductAttributeSet;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductWithAttributeSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilterController extends Controller
{

    public function getProducts(Request $request)
    {
        $slug = $request->slug;
        $params = [];
        $filter_menus = [];
        $catInfo = ProductCategory::where('slug', $slug)->first();
        if (isset($catInfo) && !empty($catInfo)) {
            
            $products = [];
            $tmp = [];
            $tmp['id'] = $catInfo->id;
            $tmp['name'] = $catInfo->name;
            $tmp['slug'] = $catInfo->slug;
            $tmp['description'] = $catInfo->description;
            $tmp['meta_title'] = $catInfo->meta_title;
            $tmp['meta_keyword'] = $catInfo->meta_keyword;
            $tmp['meta_description'] = $catInfo->meta_description;

            if (!Storage::exists($catInfo->image)) {
                $path               = asset('userImage/no_Image.jpg');
            } else {
                $url                = Storage::url($catInfo->image);
                $path               = asset($url);
            }
            $tmp['image'] = $path;
            $ind = [];
            if( isset( $catInfo->parentIndustry ) && !empty( $catInfo->parentIndustry )) {
                $ind[] = array( 
                    'id' => $catInfo->parentIndustry->id, 
                    'title' => $catInfo->parentIndustry->title, 
                    'slug' => $catInfo->parentIndustry->slug, 
                );
                if( isset( $catInfo->parentIndustry->parent ) && !empty( $catInfo->parentIndustry->parent ) ) {
                    $ind[] = array( 
                        'id' => $catInfo->parentIndustry->parent->id, 
                        'title' => $catInfo->parentIndustry->parent->title, 
                        'slug' => $catInfo->parentIndustry->parent->slug, 
                    );
                }

            }
            $tmp['industrial'] = $ind;
            $tmp['child'] = [];

            if (isset($catInfo->childCategory) && !empty($catInfo->childCategory)) {
                foreach ($catInfo->childCategory as $cat) {
                    $tmp1 = [];
                    $tmp1['id'] = $cat->id;
                    $tmp1['name'] = $cat->name;
                    $tmp1['slug'] = $cat->slug;
                    $tmp1['description'] = $cat->description;
                    $tmp1['meta_title'] = $cat->meta_title;
                    $tmp1['meta_keyword'] = $cat->meta_keyword;
                    $tmp1['meta_description'] = $cat->meta_description;

                    if (!Storage::exists($cat->image)) {
                        $path               = asset('userImage/no_Image.jpg');
                    } else {
                        $url                = Storage::url($cat->image);
                        $path               = asset($url);
                    }
                    $tmp1['image'] = $path;

                    $tmp['child'][] = $tmp1;
                }
            }
           
            if( isset( $catInfo->products ) && count($catInfo->products) > 0 ) {
                $products = $catInfo->products;
                foreach($products as $key=>$val)
                {
                    if (!Storage::exists($val->base_image)) {
                        $path               = asset('userImage/no_Image.jpg');
                    } else {
                        $url                = Storage::url($val->base_image);
                        $path               = asset($url);
                    }
                    $val['base_image'] = $path;
                }
            }
            $filter_menus = $catInfo->filterMenus ?? [];
            $tmp['filter_menus'] = $filter_menus;
            $tmp['products'] = $products;
            $params[] = $tmp;
        }

        return $params;
    }
    public function getFilterProducts(Request $request)
    {

        $filter_category        = $request->category;
        $filter_attribute       = $request->filter_id;
        $filter_attribute_array = [];
        if (isset($filter_attribute) && !empty($filter_attribute)) {
            
            $filter_attribute_array = explode("-", $filter_attribute);
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
        $total = Product::select('products.*')->where('products.status', 'published')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('product_categories as parent', 'parent.id', '=', 'product_categories.parent_id')
            ->when($filter_category != '', function ($q) use ($filter_category) {
                $q->where(function ($query) use ($filter_category) {
                    return $query->where('product_categories.slug', $filter_category)->orWhere('parent.slug', $filter_category);
                });
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
                    return $query->where('product_categories.slug', $filter_category)->orWhere('parent.slug', $filter_category);
                });
            })
            ->when($filter_attribute != '', function ($q) use ($productAttrNames) {
                $q->join('product_with_attribute_sets', 'product_with_attribute_sets.product_id', '=', 'products.id');
                return $q->whereIn('product_with_attribute_sets.attribute_values', $productAttrNames);
            })
            ->groupBy('products.id')
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
                $pro['wood_type']       = $items->wood_type;
                $pro['finishing']       = $items->finishing;
                $pro['description']     = $items->description;
                $pro['specification']   = $items->specification;
                $pro['stock_status']    = $items->stock_status;
                $pro['is_featured']     = $items->is_featured;
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
        $to = count($details);

        return array('products' => $tmp, 'total_count' => $total, 'from' => ($total == 0 ? '0' : '1'), 'to' => $to);
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
                    $attrData['id'] = $attributeData->id;
                    $attrData['title'] = $attributeData->title;
                    $attrData['slug'] = $attributeData->slug;
                    $attrData['items'] = $dataVal;
        
                    $pro['attributes'][] =   $attrData;

                }

        }
       
        $related_arr                    = [];
        $pro['meta'] = $items->productMeta;
        return $pro;
    }
    public function getOtherCategories(Request $request)
    {
        $category       = $request->category;
        $otherCategory   = ProductCategory::select('id', 'name', 'slug','image')
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
                    // $url                = Storage::url($imagePath);
                    $path               = asset($imagePath);
                }

                $tmp['image'] = $path;

                $data[] = $tmp;

            }
        } 
        return $data;
    }
    public function getDynamicFilterCategory(Request $request)
    {
        $category_slug = $request->category_slug;

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
            $filterData = ProductAttributeSet::select('product_attribute_sets.*')
                            ->join('product_categories', 'product_categories.id', '=', 'product_attribute_sets.product_category_id')
                            ->join('products', function($join){
                                $join->on('products.category_id', '=', 'product_categories.id');
                                $join->orOn('products.category_id', '=', 'product_categories.parent_id');
                            })
                            ->where('product_categories.slug', $category_slug )
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
  
}

