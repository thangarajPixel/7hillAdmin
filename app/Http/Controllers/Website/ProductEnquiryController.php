<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\ProductEnquiryMail;
use App\Models\ProductEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
class ProductEnquiryController extends Controller
{
    public function store(Request $requests)
    {
      
        $validator = Validator::make($requests->all(), [
            'name'              => 'required',
            'email'             => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'mobile'            => 'required|numeric|digits:10',
            'product_id'        => 'required|numeric',
            'city'              => 'required',
        ]);
        if ($validator->fails()) {
            return failedCall($validator->messages());
        }
      
        $data                       = new ProductEnquiry();
        $data->name                 = $requests->name;
        $data->mobile               = $requests->mobile;
        $data->email                = $requests->email;
        $data->product_id           = $requests->product_id;
        $data->city                 = $requests->city;
        $data->company_name         = $requests->company_name;
        $res = $data->save();
        if($res)
        {
            $details = [
                'name'                  =>$requests->name,
                'email'                 =>$requests->email,
                'mobile'                =>$requests->mobile,
                'product'               =>$requests->product_id,
                'city'                  =>$requests->city,
                'company_name'          =>$requests->company_name,
            ];
            try{
                $sent_mail = "santhoshd.pixel@gmail.com";

                Mail::to($sent_mail)->send(new ProductEnquiryMail($details));
            }catch(\Exception $e){
                $message = 'Product Enquiry submit successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                return response()->json(['Status'=>200,'Errors'=>false,'Message'=>$message]);
            }
        return response()->json(['Status'=>200,'Errors'=>false,'Message'=>'Thanks for reach us, our team will get back to you shortly']);

        }
        $error = 1;
        return response()->json(['error'=>$error,'message'=>"something  went wrong."]);
    }
}
