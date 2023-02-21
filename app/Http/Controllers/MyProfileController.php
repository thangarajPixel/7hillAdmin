<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class MyProfileController extends Controller
{
    public function index(Request $request)
    {
        $breadCrum = array('Authentication', 'My Account');
        $title      = 'My Account';
        $tab        = 'myaccount';
        return view('platform.my_profile.index', compact( 'breadCrum', 'title', 'tab'));
    }

    public function getPasswordTab(Request $request)
    {
        $breadCrum = array('Authentication', 'Change Password');
        $title      = 'Change Password';
        $tab        = 'password';
        return view('platform.my_profile.index', compact( 'breadCrum', 'title', 'tab'));
    }

    public function getTab(Request $request )
    {
        $id     = Auth::id();
        $data   = User::find($id);
        $tabType        = $request->tabType;
        return view( 'platform.my_profile._'.$tabType,compact('data'));
    }

    public function saveForm(Request $request)
    {
        
        $id             = $request->id;
        $tabType        = $request['tab_name'];
        if( $tabType == "myaccount")
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id . ',id',
                'mobile_number' => 'required|numeric|digits:10|unique:users,mobile_no,'. $id . ',id'
               
            ]);

            if ($validator->passes()) {
            
                $ins['name']            = $request->name;
                $ins['mobile_no']       = $request->mobile_number;
                $ins['address']         = $request->address;
                $path = '';
                if ($request->hasFile('profile_image')) {
                    $filename       = time() . '_' . $request->profile_image->getClientOriginalName();
                    $folder_name    = 'user/' . $request->email . '/profile/';
                    
                    $existID = User::find($id);
                    $deleted_file = $existID->image;
                    if($id)
                    {
                        $existID = User::find($id);
                        $deleted_file = $existID->image;
                        if(File::exists($deleted_file)) {
                            File::deleteDirectory($deleted_file);
                        }
                    }
                    if (!file_exists($folder_name)) {
                        mkdir($folder_name, 777, true);
                    }
                   
                    $path           = $folder_name . $filename;
                    $request->profile_image->move(public_path($folder_name), $filename);
                    $ins['image']   = $path;
                }
                if ($request->image_remove_image == "yes") {
                    $ins['image'] = '';
                }
    
                $error = 0;
                $user = Auth::user();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile_no = $request->mobile_number;
                $user->image = $path;
                $user->save();
                // User::updateOrCreate(['id' => $id],$ins);
                $message = (isset($id) && !empty($id)) ? 'Updated Successfully' :'Added successfully';
            } else {
                $error = 1;
                $message = $validator->errors()->all();
            }
    
        } else{
            
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
               
            ]);
            if ($validator->passes()) {
                
                if ((Hash::check($request->get('old_password'), Auth::user()->password))) {

                    $ins['password']            = Hash::make($request->password);
                    $error = 0;
                    $info = User::updateOrCreate(['id' => $id],$ins);
                    $message = (isset($id) && !empty($id)) ? 'Updated Successfully' :'Added successfully';

                } else {
                    $error = 1;
                    $message = "Old password dons't match";
                    return response()->json(['error'=> $error, 'message' => $message]);
                }

            } else {
                $error = 1;
                $message = $validator->errors()->all();
                return response()->json(['error'=> $error, 'message' => $message]);
            }
        }
      
        return response()->json(['error'=> $error, 'message' => $message, 'tabType' => $tabType]);
    }
    public function saveFormPassword(Request $request)
    {
        dd($request->all());
    }
}
