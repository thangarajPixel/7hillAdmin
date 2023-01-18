<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndustrialController extends Controller
{
    public function index(Request $request)
    { 
        
        // if ($request->ajax()) {
        //     $data = MainCategory::select('main_categories.*','users.name as users_name')->join('users', 'users.id', '=', 'main_categories.added_by');
        //     $status = $request->get('status');
        //     $keywords = $request->get('search')['value'];
        //     $datatables =  Datatables::of($data)
        //         ->filter(function ($query) use ($keywords, $status) {
        //             if ($status) {
        //                 return $query->where('main_categories.status', '=', "$status");
        //             }
        //             if ($keywords) {
        //                 $date = date('Y-m-d', strtotime($keywords));
        //                 return $query->where('main_categories.category_name', 'like', "%{$keywords}%")->orWhere('users.name', 'like', "%{$keywords}%")->orWhere('main_categories.description', 'like', "%{$keywords}%")->orWhere('main_categories.slug', 'like', "%{$keywords}%")->orWhereDate("main_categories.created_at", $date);
        //             }
        //         })
        //         ->addIndexColumn()
        //         ->editColumn('image', function ($row) {
        //             if ($row->image) {

        //                 $path = asset($row->image);
        //                 $image = '<div class="symbol symbol-45px me-5"><img src="' . $path . '" alt="" /><div>';
        //             } else {
        //                 $path = asset('userImage/no_Image.jpg');
        //                 $image = '<div class="symbol symbol-45px me-5"><img src="' . $path . '" alt="" /><div>';
        //             }
        //             return $image;
        //         })
        //         ->editColumn('status', function ($row) {
        //             $status = '<a href="javascript:void(0);" class="badge badge-light-'.(($row->status == 'published') ? 'success': 'danger').'" tooltip="Click to '.(($row->status == 'published') ? 'Unpublish' : 'Publish').'" onclick="return commonChangeStatus(' . $row->id . ',\''.(($row->status == 'published') ? 'unpublished': 'published').'\', \'main_category\')">'.ucfirst($row->status).'</a>';
        //             return $status;
        //         })

        //         ->editColumn('created_at', function ($row) {
        //             $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $row['created_at'])->format('d-m-Y');
        //             return $created_at;
        //         })

        //         ->addColumn('action', function ($row) {
        //             $edit_btn = '<a href="javascript:void(0);" onclick="return  openForm(\'main_category\',' . $row->id . ')" class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px" > 
        //             <i class="fa fa-edit"></i>
        //         </a>';
        //             $del_btn = '<a href="javascript:void(0);" onclick="return commonDelete(' . $row->id . ', \'main_category\')" class="btn btn-icon btn-active-danger btn-light-danger mx-1 w-30px h-30px" > 
        //         <i class="fa fa-trash"></i></a>';

        //             return $edit_btn . $del_btn;
        //         })
        //         ->rawColumns(['action', 'status', 'image']);
        //     return $datatables->make(true);
        // }
        $breadCrum  = array('Masters', 'Dynamic Category Industrial');
        $title      = 'Category Industrial';
        return view('platform.product_industrial.index', compact('breadCrum', 'title'));

    }
}
