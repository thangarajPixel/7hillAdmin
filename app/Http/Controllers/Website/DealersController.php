<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\DealersMail;
use App\Models\Dealers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
class DealersController extends Controller
{
    public function store(Request $requests)
    {
      
        $validator = Validator::make($requests->all(), [
            'name'              => 'required',
            'email'             => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'mobile'            => 'required|numeric|digits:10',
            'enquiry_name'      => 'required',
            'state'             => 'required',
            'location'          => 'required',
        ]);
        if ($validator->fails()) {
            return failedCall($validator->messages());
        }
      
        $data                       = new Dealers;
        $data->name                 = $requests->name;
        $data->mobile               = $requests->mobile;
        $data->email                = $requests->email;
        $data->enquiry_name         = $requests->enquiry_name;
        $data->state                = $requests->state;
        $data->location             = $requests->location;
        $res = $data->save();
        if($res)
        {
            $details = [
                'name'                  =>$requests->name,
                'email'                 =>$requests->email,
                'mobile'                =>$requests->mobile,
                'enquiry_name'          =>$requests->enquiry_name,
                'state'                 =>$requests->state,
                'location'              =>$requests->location,
            ];
            try{
                $sent_mail = config('constant.mail');
                Mail::to($sent_mail)->send(new DealersMail($details));
            }catch(\Exception $e){
                $message = 'Thanks for reach us, our team will get back to you shortly. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                return response()->json(['Status'=>200,'Errors'=>false,'Message'=>$message]);
            }
        return response()->json(['Status'=>200,'Errors'=>false,'Message'=>'Thanks for reach us, our team will get back to you shortly']);

        }
        $error = 1;
        return response()->json(['error'=>$error,'message'=>"something  went wrong."]);
    }
}