<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industrial;

class MenuController extends Controller
{
    public function getAllMenu()
    {

        $data = Industrial::where('parent_id',0)->where('status', 'published')->get();
        $params = [];
        if( isset( $data ) && !empty( $data ) ) {
            foreach ($data as $item ) {
                
                $tmp= [];
                $tmp['id'] = $item->id;
                $tmp['title'] = $item->title;
                $tmp['slug'] = $item->slug;
                if(!empty($item->image))
                {
                    $tmp['image'] = asset($item->image);
                }
                else{
                    $tmp['image'] =  asset('userImage/no_Image.jpg');
                }
                if(!empty($item->icon))
                {
                    $tmp['icon'] = asset($item->icon);
                }
                else{
                    $tmp['icon'] =  asset('userImage/no_Image.jpg');
                }
                
                if( isset( $item->childOnlyNames ) && !empty( $item->childOnlyNames ) && count($item->childOnlyNames) > 0 ) {
                    foreach ( $item->childOnlyNames as $child ) {
                        
                        $tmp1 = [];
                        $tmp1['id'] = $child->id;
                        $tmp1['title'] = $child->title;
                        $tmp1['slug'] = $child->slug;
                        $tmp1['description'] = $child->description;
                        // $tmp1['image'] = $child->image;
                        // dd($child);
                        if(!empty($child->image))
                        {
                            $tmp1['image'] = asset($child->image);
                        }
                        else{
                            $tmp1['image'] = asset('userImage/no_Image.jpg');
                        }
                        if(!empty($child->icon))
                        {
                            $tmp1['icon'] = asset($child->icon);
                        }
                        else{
                            $tmp1['icon'] =  asset('userImage/no_Image.jpg');
                        }
                        if( isset( $child->childCategory ) && !empty( $child->childCategory ) ) {
                            foreach ( $child->childCategory as $val1 ) {
                                // dd($val1);
                                if(!empty($val1->image))
                                {
                                    $val1->image = asset($val1->image);
                                }
                                else{
                                    $val1->image = asset('userImage/no_Image.jpg');
                                }
                                if(!empty($val1->icon))
                                {
                                    $val1->icon = asset($val1->icon);
                                }
                                else{
                                    $val1->icon = asset('userImage/no_Image.jpg');
                                }

                                if(!empty($val1->banner_image))
                                {
                                    $val1->banner_image = asset($val1->banner_image);
                                }
                                else{
                                    $val1->banner_image = asset('userImage/7hillbanner.jpg');
                                }
                            }
                        }
                        $tmp1['child'] = $child->childCategory;
                        /**
                         * check category exist
                         */
                        $tmp['child'][] = $tmp1;
                    }
                } else {
                    /**
                     * for home funrniture menus
                     */
                    if(!empty($item->childCategory) && isset($item->childCategory))
                    {
                        foreach($item->childCategory as $key=>$val)
                        {
                            
                            if(!empty($val->image))
                            {
                                $val->image = asset($val->image);
                            }
                            else{
                                $val->image = asset('userImage/no_Image.jpg');
                            }
                            if(!empty($val->icon))
                            {
                                $val->icon = asset($val->icon);
                            }
                            else{
                                $val->icon = asset('userImage/no_Image.jpg');
                            }

                            if(!empty($val->banner_image))
                            {
                                $val->banner_image = asset($val->banner_image);
                            }
                            else{
                                $val->banner_image = asset('userImage/7hillbanner.jpg');
                            }
                            // dd($val->image);
                        }
                    }
                    
                    $tmp['child'] = $item->childCategory;
                }
              
                $params[] = $tmp;
            }
        }
        return $params;
    }
    
}
