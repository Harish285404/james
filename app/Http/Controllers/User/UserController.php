<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use DataTables;
use Hash;

class UserController extends Controller
{
  



public function user(){

  return view('User.dashboard');
}

 public function categorylist()
    {
          return view('User.categorylist');
    }



        public function singlecategoryview()
    {
          return view('User.singlecategoryview');
    }


   public function productlist()
    {
          return view('User.productlist');
    }


    public function singleproduct()
    {
          return view('User.singleproduct');
    }


     public function stocklist()
    {
          return view('User.stocklist');
    }


    public function reports()
    {
          return view('User.reports');
    }

      public function profile()
    {
           $id = Auth::user()->id;

        $data = User::where('id', '=', $id)->get();
    // dd($data);
        $data = compact('data');
          return view('User.profile')->with($data);
    }


      public function update(Request $request)
  {
    
    // dd($request->all());

    $request->validate([
      'first_name' => 'required',
      'last_name' => 'required',
      'phone_number' => 'required|max:10',
      'email' => 'required',
      

    ]);

    $User = User::find($request->id);
     $User->first_name = $request->first_name;
      $User->last_name = $request->last_name;
    $User->phone_number = $request->phone_number;
    $User->email = $request->email;
   
    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'User/images';

      $image->move($destinationPath, $name);
      $User->image = $name;
    }

    $update = $User->save();

    if ($update) {
      return redirect()->back()->with('message', 'Profile Successfully Updated!');
    }
  }

   public function changepassword()
    {
          return view('User.changepassword');
    } 

  public function forgotpassword(Request $request)
  {
    $request->validate([
      'oldpass' => 'required',
      'newpass' => 'min:8|required',
      'cnewpass' => 'min:8|required',
    ]);
    
    if (Hash::check($request->oldpass, auth()->user()->password)) {
      if (!Hash::check($request->newpass, auth()->user()->password)) {
        if ($request->newpass == $request->cnewpass) {


          $user = User::find(auth()->id());
          $user->update([
            'password' => bcrypt($request->newpass),
            'plane_password' =>($request->newpass)
          ]);

          return redirect()->back()->with('message', 'Password updated successfully!');
        } else {

          return redirect()->back()->with('message', 'Password mismatched!');
        }
      }

      return redirect()->back()->with('message', 'New password can not be the old password!');
    }

    return redirect()->back()->with('message', 'Old password does not matched!');
  }




public function signout() {
    Auth::logout();
   return view('auth.login');
  }





}
