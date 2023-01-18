<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class ContactController extends Controller
{
    public function store(Request $requests)
    {
      
        $validator = Validator::make($requests->all(), [
            'name' => 'required',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'mobile' => 'required|numeric|digits:10',
        ]);
        if ($validator->fails()) {
            return failedCall($validator->messages());
        }
      
        $data = new Contact;
        $data->name = $requests->name;
        $data->mobile = $requests->mobile;
        $data->email = $requests->email;
        $data->message = $requests->message;
        $res = $data->save();
        if($res)
        {
            $details = [
                'name'          =>$requests->name,
                'email'         =>$requests->email,
                'mobile'        =>$requests->mobile,
                'message'       =>$requests->message,
            ];
            try{
                $sent_mail = "santhoshd.pixel@gmail.com";

                Mail::to($sent_mail)->send(new ContactMail($details));
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
