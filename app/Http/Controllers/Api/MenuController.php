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
                    $tmp['image'] = '';
                }
                
                if( isset( $item->childOnlyNames ) && !empty( $item->childOnlyNames ) && count($item->childOnlyNames) > 0 ) {
                    foreach ( $item->childOnlyNames as $child ) {
                        
                        $tmp1 = [];
                        $tmp1['id'] = $child->id;
                        $tmp1['title'] = $child->title;
                        $tmp1['slug'] = $child->slug;
                        $tmp1['description'] = $child->description;
                        $tmp1['image'] = $child->image;
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
                    $tmp['child'] = $item->childCategory;
                }
              
                $params[] = $tmp;
            }
        }
        return $params;
    }
    
}
