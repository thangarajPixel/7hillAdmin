<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industrial;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $industrial = $request->slug ?? '';

        if(!empty($industrial))
        {
            $data = Industrial::where('slug',$industrial)

            ->where('status','published')
            ->get();
            $params = [];
            $tmp= [];
            
            if(isset($data) && !empty($data))
            {
                foreach($data as $key=>$item)
                {
                    //industrial Data Get
                    $tmp['id']         = $item->id;
                    $tmp['title']      = $item->title;
                    $tmp['slug']       = $item->slug;
                    $tmp['parent_id']  = $item->parent_id;
                    if(!empty($item->image))
                    {
                        $tmp['image'] = asset($item->image);
                    }
                    else{
                        $tmp['image'] = '';
                    }

                    // industrial Category data Get
                    if( isset( $item->childCategory ) && !empty( $item->childCategory ) ) 
                    {
                       
                        $tmp['parentCategory']= [];
                        foreach ( $item->childCategory as $parentCat ) {
                            $tmpCate['id'] = $parentCat->id;
                            $tmpCate['name'] = $parentCat->name;
                            $tmpCate['slug'] = $parentCat->slug;
                            $tmpCate['parent_id'] = $parentCat->parent_id;
                            $tmpCate['industrial_id'] = $parentCat->industrial_id;
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
                    $params[] = $tmp ;
                  
                }
                
            }
            return response()->json(['data'=>$params,]);
        }
        else{
            $data = Industrial::where('status','published')->get();
            $params = [];
            $tmp= [];
            
            if(isset($data) && !empty($data))
            {
                foreach($data as $key=>$item)
                {
                    //industrial Data Get
                    $tmp['id']         = $item->id;
                    $tmp['title']      = $item->title;
                    $tmp['slug']       = $item->slug;
                    $tmp['parent_id']  = $item->parent_id;
                    if(!empty($item->image))
                    {
                        $tmp['image'] = asset($item->image);
                    }
                    else{
                        $tmp['image'] = '';
                    }

                    // industrial Category data Get
                    if( isset( $item->childCategory ) && !empty( $item->childCategory ) ) 
                    {
                       
                        $tmp['parentCategory']= [];
                        foreach ( $item->childCategory as $parentCat ) {
                            $tmpCate['id'] = $parentCat->id;
                            $tmpCate['name'] = $parentCat->name;
                            $tmpCate['slug'] = $parentCat->slug;
                            $tmpCate['parent_id'] = $parentCat->parent_id;
                            $tmpCate['industrial_id'] = $parentCat->industrial_id;
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
                    $params[] = $tmp ;
                  
                }
                
            }
            return response()->json(['data'=>$params]);

        }
        

    }
}
