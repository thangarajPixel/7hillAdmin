<?php

namespace App\Http\Controllers;

use App\Models\Industrial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Carbon\Carbon;
use Excel;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Str;
use Image;
class IndustrialController extends Controller
{
    public function index(Request $request)
    { 
        
        if ($request->ajax()) {
            $data = Industrial::select('industrials.*','users.name as users_name',
            DB::raw('IF(7hill_industrials.parent_id = 0, "Parent", 7hill_parent_category.title ) as parent_name '))
            ->join('users', 'users.id', '=', 'industrials.added_by')
            ->leftJoin('industrials as parent_category', 'parent_category.id', '=', 'industrials.parent_id');
            $status = $request->get('status');
            $keywords = $request->get('search')['value'];
            $datatables =  Datatables::of($data)
                ->filter(function ($query) use ($keywords, $status) {
                    if ($status) {
                        return $query->where('industrials.status', '=', "$status");
                    }
                    if ($keywords) {
                        $date = date('Y-m-d', strtotime($keywords));
                        return $query->where('industrials.title', 'like', "%{$keywords}%")->orWhere('users.name', 'like', "%{$keywords}%")->orWhere('industrials.description', 'like', "%{$keywords}%")->orWhere('industrials.slug', 'like', "%{$keywords}%")->orWhereDate("industrials.created_at", $date);
                    }
                })
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    if ($row->image) {

                        $path = asset($row->image);
                        $image = '<div class="symbol symbol-45px me-5"><img src="' . $path . '" alt="" /><div>';
                    } else {
                        $path = asset('userImage/no_Image.jpg');
                        $image = '<div class="symbol symbol-45px me-5"><img src="' . $path . '" alt="" /><div>';
                    }
                    return $image;
                })
                ->editColumn('status', function ($row) {
                    $status = '<a href="javascript:void(0);" class="badge badge-light-'.(($row->status == 'published') ? 'success': 'danger')
                    .'" tooltip="Click to '.(($row->status == 'published') ? 'Unpublish' : 'Publish').'" 
                    onclick="return commonChangeStatus(' . $row->id . ',\''.(($row->status == 'published') ? 'unpublished': 'published')
                    .'\', \'industrial-module\')">'.ucfirst($row->status).'</a>';
                    return $status;
                })

                ->editColumn('created_at', function ($row) {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $row['created_at'])->format('d-m-Y');
                    return $created_at;
                })

                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="javascript:void(0);" onclick="return  openForm(\'industrial-module\',' . $row->id . ')" class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px" > 
                    <i class="fa fa-edit"></i>
                </a>';
                    $del_btn = '<a href="javascript:void(0);" onclick="return commonDelete(' . $row->id . ', \'industrial-module\')" class="btn btn-icon btn-active-danger btn-light-danger mx-1 w-30px h-30px" > 
                <i class="fa fa-trash"></i></a>';

                    return $edit_btn . $del_btn;
                })
                ->rawColumns(['action', 'status', 'image']);
            return $datatables->make(true);
        }
        $breadCrum  = array('Masters', 'Dynamic Category Industrial');
        $title      = 'Category Industrial';
        return view('platform.product_industrial.index', compact('breadCrum', 'title'));

    }
    public function modalAddEdit(Request $request)
    {

        $id                 = $request->id;
        $from               = $request->from;
        $info               = '';
        $modal_title        = 'Add Industrial';
        $productCategory    = Industrial::select('id','title','slug')->where('parent_id', 0)->get();
        if (isset($id) && !empty($id)) {
            $info           = Industrial::find($id);
            $modal_title    = 'Update Industrial';
        }
        return view('platform.product_industrial.add_edit_modal', compact('info', 'modal_title', 'from','productCategory'));
        
    }
    public function saveForm(Request $request,$id = null)
    {
        $id             = $request->id;
        $parent_id      = $request->parent_category;
        $validator      = Validator::make($request->all(), [
                                'title' => 'required|string|unique:industrials,title,' . $id . ',id,deleted_at,NULL',
                                'avatar' => 'mimes:jpeg,png,jpg',
                            ]);
        $category_id      = '';
        if ($validator->passes()) {
            
            $ins['title']               = $request->title;
            $ins['description']         = $request->description;
            $ins['slug']                = \Str::slug($request->title);
            $ins['meta_title']          = $request->meta_title ?? '';
            $ins['meta_keyword']        = $request->meta_keyword ?? '';
            $ins['meta_description']    = $request->meta_description ?? '';
            $ins['sorting_order']       = $request->order_by ?? 0;
            $ins['added_by']            = auth()->user()->id;
           
            if($request->status == "1")
            {
                $ins['status']          = 'published';
            } else {
                $ins['status']          = 'unpublished';
            }
            if( !$request->is_parent ) {
                $ins['parent_id'] = $request->parent_category;
            } else {
                $ins['parent_id'] = 0;
            }

            if ($request->image_remove_image == "no") {
                $directory = 'upload/category/'.$id;
                \File::deleteDirectory(public_path($directory));
                $ins['image'] = '';
            }
            
            $error                      = 0;
            $info                       = Industrial::updateOrCreate(['id' => $id], $ins);
            $category_id                  = $info->id;

            if ($request->image_remove_image == "no") {
                $directory = 'upload/category/'.$category_id;
                \File::deleteDirectory(public_path($directory));
            }

            if($request->hasFile('avatar'))
            {
                $directory = 'upload/category/'.$category_id;
                \File::deleteDirectory(public_path($directory));

                $file = $request->file('avatar');
                $imageName = uniqid().preg_replace('/[^A-Za-z0-9\-]/','',$file->getClientOriginalName());
                if(!is_dir(public_path($directory."/")))
                {
                    mkdir(public_path($directory."/"),0775,true);
                }
                $mainCategory   = "upload/category/".$category_id."/".$imageName;
                $file->move(public_path($directory),$imageName);

                $info->image       = $mainCategory;
                $info->save();
            }
           
            $message                    = (isset($id) && !empty($id)) ? 'Updated Successfully' : 'Added successfully';
        } else {
            $error                      = 1;
            $message                    = $validator->errors()->all();
        }
        return response()->json(['error' => $error, 'message' => $message, 'category_id' => $category_id]);
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status         = $request->status;
        $info           = Industrial::find($id);
        $info->status   = $status;
        $info->update();
        return response()->json(['message'=>"You changed the Industrial status!",'status'=>1]);
    }
    public function delete(Request $request)
    {
        $id         = $request->id;
        $info       = Industrial::find($id);
        $info->delete();
        $directory = 'industrial/'.$id;
        Storage::deleteDirectory('public/'.$directory); 
        return response()->json(['message'=>"Successfully deleted Industrial!",'status'=>1]);
    }
}
