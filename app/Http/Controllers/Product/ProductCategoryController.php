<?php

namespace App\Http\Controllers\Product;

use App\Exports\ProductCategoryExport;
use App\Http\Controllers\Controller;
use App\Models\CategoryMetaTags;
use App\Models\Industrial;
use Illuminate\Http\Request;
use App\Models\Product\ProductCategory;
use Illuminate\Validation\Rule;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Auth;
use Excel;
use Illuminate\Support\Facades\Storage;
use PDF;
use Image;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $title                  = "Product Category";
        $breadCrum              = array('Products', 'Product Categories');
        if ($request->ajax()) {
            $data               = ProductCategory::select('product_categories.*','users.name as users_name', 
            DB::raw('IF(7hill_product_categories.parent_id = 0, "Parent", 7hill_parent_category.name ) as parent_name '))
                                    ->join('users', 'users.id', '=', 'product_categories.added_by')
                                    ->leftJoin('product_categories as parent_category', 'parent_category.id', '=', 'product_categories.parent_id');
            $taxSearch          = '';
            $status             = $request->get('status');
            $taxSearch          = $request->get('filter_tax');
            $keywords           = $request->get('search')['value'];
            $datatables         =  Datatables::of($data)
                ->filter(function ($query) use ($keywords, $status,$taxSearch) {
                    
                    return $query->when( $status != '', function($q) use($status) {
                        $q->where('product_categories.status', '=', "$status");
                    
                    })->when($keywords != '',function($q) use ($keywords) {
                        $date = date('Y-m-d', strtotime($keywords));
                        $q->where('product_categories.name', 'like', "%{$keywords}%")
                                    ->orWhere('users.name', 'like', "%{$keywords}%")
                                    ->orWhere('product_categories.description', 'like', "%{$keywords}%")
                                    ->orWhere('parent_category.name', 'like', "%{$keywords}%")
                                    ->orWhereDate("product_categories.created_at", $date);
                    });
                })
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $status = '<a href="javascript:void(0);" class="badge badge-light-'.(($row->status == 'published') ? 'success': 'danger').'" tooltip="Click to '.(($row->status == 'published') ? 'Unpublish' : 'Publish').'" onclick="return commonChangeStatus(' . $row->id . ', \''.(($row->status == 'published') ? 'unpublished': 'published').'\', \'product-category\')">'.ucfirst($row->status).'</a>';
                    return $status;
                })
                ->editColumn('tax', function($row){
                    return $row->tax ?? 'No';
                })
                
                ->editColumn('created_at', function ($row) {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $row['created_at'])->format('d-m-Y');
                    return $created_at;
                })
                ->addColumn('action', function ($row) {
                    $edit_btn = '<a href="javascript:void(0);" onclick="return  openForm(\'product-category\',' . $row->id . ')" class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px" > 
                    <i class="fa fa-edit"></i>
                </a>';
                    $del_btn = '<a href="javascript:void(0);" onclick="return commonDelete(' . $row->id . ', \'product-category\')" class="btn btn-icon btn-active-danger btn-light-danger mx-1 w-30px h-30px" > 
                <i class="fa fa-trash"></i></a>';
                    return $edit_btn . $del_btn;
                })
                ->rawColumns(['action', 'status', 'image']);
            return $datatables->make(true);
        }
        return view('platform.product_category.index', compact('title','breadCrum'));
    }

    public function modalAddEdit(Request $request)
    {
        $title              = "Add Product Categories";
        $breadCrum          = array('Products', 'Add Product Categories');

        $id                 = $request->id;
        $from               = $request->from;
        $info               = '';
        $modal_title        = 'Add Product Category';
        $industrial         = Industrial::where('status', 'published')->get();
        $productCategory    = ProductCategory::where('status', 'published')->where('parent_id', 0)->get();
        if (isset($id) && !empty($id)) {
            $info           = ProductCategory::find($id);

            $modal_title    = 'Update Product Category';
        }
        foreach($industrial as $key=>$val)
        {
            if($val['parent_id'] == 0)
            {
                $val['title'] = $val['title']." - Parent";
            }
            else{
                if($val['parent_id'])
                {
                    $datass = $val->selectOption;
                    $val['title'] = $val['title']." - " . $val->selectOption->title;
                }
            }

        }

        return view('platform.product_category.form.add_edit_form', compact('modal_title', 'breadCrum', 'info', 'from', 'industrial','productCategory'));
    }
    public function checkIndustrial(Request $request)
    {
        $data  = '';
        if($request->data)
        {
            $data   = Industrial::select('id','title')->where('status', 'published')->where('parent_id', $request->data)->get();
        }
        // $data = $request->all();
        return response()->json(['data'=>$data]);
    }
    public function saveForm(Request $request,$id = null)
    {
        $id             = $request->id;
        $validator      = Validator::make($request->all(), [
            'name' => 'required|string',
            'industrial_category' => 'required',
            'slug' => 'string|unique:product_categories,slug,'. $id . ',id,deleted_at,NULL',
            'avatar' => 'mimes:jpeg,png,jpg',
        ]);

        $categoryId         = '';
        if ($validator->passes()) {
            if ($request->image_remove_logo == "yes") {
                $ins['image'] = '';
            }
          
            if( !$request->is_parent ) {
                $ins['parent_id'] = $request->parent_category;
            } else {
                $ins['parent_id'] = 0;
            }

            if( !$id ) {
                $ins['added_by'] = Auth::id();
            }else {
                $ins['updated_at'] = Auth::id();
            }
            $indInfo = Industrial::find($request->industrial_category);
            $ins['industrial_id'] = $request->industrial_category;

            $ins['name'] = $request->name;
            $ins['description'] = $request->description;
            $ins['order_by'] = $request->order_by ?? 0;

            if($request->status)
            {
                $ins['status']          = 'published';
            } else {
                $ins['status']          = 'unpublished';
            }

            $ins['slug']                = \Str::slug($request->name.' '.$indInfo->slug);
            $ins['meta_title']          = $request->meta_title ?? '';
            $ins['meta_keyword']        = $request->meta_keyword ?? '';
            $ins['meta_description']    = $request->meta_description ?? '';
            $ins['sorting_order']       = $request->order_by ?? 0;
            $error                      = 0;
            if ($request->image_remove_image == "no") {
                $directory = 'upload/category/image/'.$id;
                \File::deleteDirectory(public_path($directory));
                $ins['image'] = '';
            }
            $categeryInfo               = ProductCategory::updateOrCreate(['id' => $id], $ins);
            $categoryId                 = $categeryInfo->id;

            if($request->hasFile('categoryImage'))
            {
                $directory = 'upload/category/image/'.$categoryId;
                \File::deleteDirectory(public_path($directory));

                $file = $request->file('categoryImage');
                $imageName = uniqid().str_replace(["(", ")"],'',$file->getClientOriginalName());
                if(!is_dir(public_path($directory."/")))
                {
                    mkdir(public_path($directory."/"),0775,true);
                }
                $mainCategory   = "upload/category/image/".$categoryId."/".$imageName;
                $file->move(public_path($directory),$imageName);

                $categeryInfo->image       = $mainCategory;
                $categeryInfo->save();
            }


            $message                    = (isset($id) && !empty($id)) ? 'Updated Successfully' : 'Added successfully';
        } else {
            $error      = 1;
            $message    = $validator->errors()->all();
        }
        return response()->json(['error' => $error, 'message' => $message, 'categoryId' => $categoryId]);
    }

    public function delete(Request $request)
    {
        $id         = $request->id;
        $info       = ProductCategory::find($id);
        $info->delete();
        $directory      = 'productCategory/'.$id;
        Storage::deleteDirectory($directory);
        return response()->json(['message'=>"Successfully deleted!",'status'=>1]);
    }

    public function changeStatus(Request $request)
    {
        
        $id             = $request->id;
        $status         = $request->status;
        $info           = ProductCategory::find($id);
        $info->status   = $status;
        $info->update();
        // echo 1;
        return response()->json(['message'=>"You changed the status!",'status'=>1]);

    }
    
    public function export()
    {
        return Excel::download(new ProductCategoryExport, 'productCategories.xlsx');
    }
    
    public function exportPdf()
    {
        $list       = ProductCategory::all();
        $pdf        = PDF::loadView('platform.exports.product.product_category_excel', array('list' => $list, 'from' => 'pdf'))->setPaper('a4', 'landscape');;
        return $pdf->download('productCategories.pdf');
    }
}
