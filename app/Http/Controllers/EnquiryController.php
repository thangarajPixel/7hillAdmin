<?php

namespace App\Http\Controllers;

use App\Exports\ProductEnquiryExport;
use App\Models\ProductEnquiry;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class EnquiryController extends Controller
{
    public function enquiryList(Request $request)
    {
        if($request->ajax())
        {
            $data = ProductEnquiry::select('product_enquiries.*','products.product_name')
            ->leftJoin('products','product_enquiries.product_id','=','products.id');
            $keywords = $request->get('search')['value'];
            $datatables = Datatables::of($data)
            ->filter(function($query)use ($keywords){
                if($keywords)
                {
                    $date = date('Y-m-d', strtotime($keywords));
                    return $query->where('product_enquiries.name','like',"%{$keywords}%")
                    ->orWhere('product_enquiries.email','like',"%{$keywords}%")
                    ->orWhere('product_enquiries.mobile','like',"%{$keywords}%")
                    ->orWhere('product_enquiries.company_name','like',"%{$keywords}%")
                    ->orWhere('product_enquiries.city','like',"%{$keywords}%")
                    ->orWhere('products.product_name','like',"%{$keywords}%");
                }
            })
            ->addIndexColumn()
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
            //     $view_btn = '<a href="javascript:void(0);" onclick="return  openForm(\'industrial-module\',' . $row->id . ')" class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px" > 
            //     <i class="fa fa-edit"></i>
            // </a>';
            $view_btn = '<a href="'.route('enquiry.products.view',$row->id).'"  class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px" > 
            <i class="fa fa-eye"></i>
        </a>';
                $del_btn = '<a href="javascript:void(0);" onclick="return commonDelete(' . $row->id . ', \'enquiry\')" class="btn btn-icon btn-active-danger btn-light-danger mx-1 w-30px h-30px" > 
            <i class="fa fa-trash"></i></a>';

                return $view_btn . $del_btn;
            })
            ->rawColumns(['action', 'status']);
            return $datatables->make(true);
        }
        $title = "Enquiry";
        $breadCrum  = array( 'Enquiry','Product Enquiry');

        return view('platform.enquiry.product.index',compact('breadCrum', 'title'));
    }
    public function productView($id)
    {
        $breadCrum          = array('Enquiry','Enquiry View');
        $title              = 'Enquiry View';
        $info               = ProductEnquiry::with('productData')->where('id',$id)->first();
        return view('platform.enquiry.product.view', compact('title', 'breadCrum','info'));

    }
    public function productEnquiryDelete(Request $request)
    {
        $data    = ProductEnquiry::where('id', $request->id)->delete();
        $message                        = 'Deleted Successfully';
        return response()->json(['message' => $message]);
    }
    public function productEnquiryexport ()
    {
        return Excel::download(new ProductEnquiryExport, 'customers.xlsx');
    }
}
