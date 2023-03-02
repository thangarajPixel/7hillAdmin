<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\CareerMail;
use App\Models\Career;
use App\Models\CareerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
class CareerController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'mobile' => 'required|numeric|digits:10',
            'file'   => 'required|mimes:doc,pdf,docx'
        ]);
        if ($validator->fails()) {
            return failedCall($validator->messages());
        }

        $data = new Career;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->cover_letter = $request->cover_letter;
        $attachement = '';
        if($request->file)
        {
            $filePath = 'website/careers';
            $path = public_path($filePath); 
            if(!file_exists($path))
            {
                mkdir($path, 0777, true);
            }
                
            if($request->hasfile('file'))
            {
                    $file = $request->file('file');
                    
                    if($file->extension() == "pfd" || "doc" || "docx")
                    {
                    $name = str_replace(["(", ")"," "],'',$file->getClientOriginalName());
                    $file->move(public_path('website/careers'), $name);  
                    $attachPath= public_path('website/careers');
                    $attachement =  $attachPath.'/'.$name;
                    }
            }
            $data->file = $name;
        }
        $res = $data->save();
    if($res)
        {
            $jobData  = Career::find($request->job_id);
            $details = [
                'name'                  =>$request->name,
                'email'                 =>$request->email,
                'mobile'                =>$request->mobile,
                'cover_letter'          =>$request->cover_letter,
                'attachment'            =>$attachement,
            ];
            try{
                $sent_mail = config('constant.mail');
                Mail::to($sent_mail)->send(new CareerMail($details));
            }catch(\Exception $e){
                $message = 'Thanks for reach us, our team will get back to you shortly. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                return response()->json(['Status'=>200,'Errors'=>false,'Message'=>$message]);
            }
            return response()->json(['Status'=>200,'Errors'=>false,'Message'=>'Thank you for Applying']);

        }
        $error = 1;
        return response()->json(['error'=>$error,'message'=>"something went wrong."]);

    }
}
