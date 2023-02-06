<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuAllResource;
use App\Http\Resources\MenuResource;
use App\Models\Industrial;
use App\Models\Product\ProductCategory;

class MenuController extends Controller
{
    
    public function getAllMenu()
    {

        $data = Industrial::where('parent_id',0)->where('status', 'published')->get();
        $params = [];
        $tmp= [];
        if( isset( $data ) && !empty( $data ) ) {
            foreach ($data as $item ) {
                $tmp['id'] = $item->id;
                $tmp['title'] = $item->title;
                $tmp['slug'] = $item->slug;
                if(!empty($item->image))
                {
                    $tmp['image'] = asset($item->image);
                }
                else{
                    $tmp['image'] = '';
                }
                // $tmp['image'] = asset($item->image);
                if( isset( $item->childCategory ) && !empty( $item->childCategory ) ) {
                    $tmp['parentCategory']= [];
                    foreach ( $item->childCategory as $parentCat ) {
                        $tmpCate['id'] = $parentCat->id;
                        $tmpCate['name'] = $parentCat->name;
                        $tmpCate['slug'] = $parentCat->slug;
                        if(!empty($parentCat->image))
                        {
                            $tmpCate['image'] =  asset($parentCat->image);
                        }
                        else{
                            $tmpCate['image'] =  '';
                        }
                        // $tmpCate['image'] =  asset($parentCat->image);
                        $tmpCate['meta_title'] = $parentCat->meta_title;
                        $tmpCate['meta_keyword'] = $parentCat->meta_keyword;
                        $tmpCate['meta_description'] = $parentCat->meta_description;
                        $tmp['parentCategory'][] = $tmpCate;
                    }
                    $tmp['childCategory'] = [];
                    if( isset( $item->childOnlyNames ) && !empty( $item->childOnlyNames ) ) {
                        foreach ( $item->childOnlyNames as $child ) {
                           
                            $tmp1['id'] = $child->id;
                            $tmp1['title'] = $child->title;
                            $tmp1['slug'] = $child->slug;
                            $tmp1['image'] = asset($child->image);
                            // $tmp1['category'] = $child->childCategory;
                            $tmp1['category'] = [];
                            if(isset($child->childCategory) && !empty($child->childCategory))
                            {
                                foreach ( $child->childCategory as $category ) {
                                    $cat['id'] = $category->id;
                                    $cat['name'] = $category->name;
                                    $cat['slug'] = $category->slug;
                                    // $cat['image'] = asset($category->image);
                                    if(!empty($category->image))
                                    {
                                        $cat['image'] = asset($category->image);
                                    }
                                    else{
                                        $cat['image'] =  '';
                                    }
                                    $tmp1['category'][] = $cat;
                                }
                            }
                            $tmp['childCategory'][] = $tmp1;
                        }
                    }
                }
                $params[] = $tmp;
            }
        }
        return $params;
        
    }
    
}
