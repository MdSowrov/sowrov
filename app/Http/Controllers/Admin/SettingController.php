<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:jpeg,bmp,png,jpg',
            'email' => 'required|email'

        ]);

            $image = $request->file('image');
            $slug = str_slug($request->name);
            $user = User::findOrfail(Auth::id());

            if(isset($image))
          {
              //make unique name for image
            $currentDate = Carbon::now()->toDatestring();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            // check catagory dir is exists
            if (!Storage::disk('public')->exists('profile'))
            {
                Storage::disk('public')->makeDirectory('profile');
            }
            // Delete Old post Image

            if (Storage::disk('public')->exists('profile/'.$user->image))
            {
                Storage::disk('public')->delete('profile/'.$user->image);
            }


            // resize image for category and upload
            $profileImage = Image::make($image)->resize(500, 500)->stream();
            Storage::disk('public')->put('profile/'.$imagename, $profileImage);

          }else{
              $imagename = $user->image;
          }
          $user->name = $request->name;
          $user->email = $request->email;
          $user->image = $imagename;
          $user->about = $request->about;
          $user->save();

          Toastr::success('Profile Successfully Updated:)', 'Success');

          return redirect()->back();
    }



    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $hashePassword = Auth::user()->password;
        if (Hash::check($request->old_password, $hashePassword))
        {
            if (!Hash::check($request->password, $hashePassword)) {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();

                Toastr::success('Password Successfully Changed:)', 'Success');

                Auth::logout();

                return redirect()->back();

            }else{
                Toastr::error('New Password cannot be the same as old password:)', 'Error');
                return redirect()->back();
            }
        }else{
            Toastr::error('Current password not match:)', 'Error');
            return redirect()->back();
        }
    }

}
