<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerifyEmail;
use App\Jobs\JobForMail;

class AuthController extends Controller
{
    public function SignUp(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed|min:8',
            'checkbox' => 'required',
            'referal_code' => 'nullable|numeric|min:8|max:8',


        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->parent_id = $request->referal_code;
        $user->reference_id=rand(11111111,99999999);
        $user->save();

        $code = rand(111111, 999999);
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_email', $user->email);
        $request->session()->put('verification_code', $code);
        $data = [
            'code' => $code,
            'subject' => 'Please Verify Your Email',
            'user_id' =>  $user->id,
            'email' => $user->email
        ];

        dispatch(new JobForMail($data));
        return view('emailverify')->with('verify_now',"success, please enter the 6-digit code sent to your email");
    }

    public function VerifyMail(Request $request)
    {
        if ( $request->session()->get('verification_code') == $request->verification_code )
        {
            $row = User::find($request->session()->get('user_id'));

            $row->email_verified_at = date('Y-m-d H:i:s');

            $row->save();
            $request->session()->flush();
            return view('Dashboard.dashboard');
        }
        else
        {
            return view('emailverify')->with('Invalid_Code','Invalid Code');
        }
    }

    public function LogIn(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect('/dashboard');
    }
    else{
        return back()->with('Invalid_Credentials','Invalid Credentials, please try again');
    }

    // return redirect('/login')->with('no_match', 'Invalid Credentials');
    }

    public function LogOut()
    {
        auth()->logout();

        return redirect('/login')->with('logout_success', 'logged out');
    }

public function loginCheck(){
    if(Auth::check()){
        return view('Dashboard.dashboard');
    }
    else{
        return view('login');
    }
}
public function signupCheck(){
    if(Auth::check()){
        return redirect('/dashboard');
    }
    else{
        return view('signup');
    }
}
public function dashboardCheck(){
    if(Auth::check()){
        return view('Dashboard.dashboard');
    }
    else{
        return redirect('login');
    }
}
public function packagesCheck(){
    if(Auth::check()){
        return view('Dashboard.packages');
    }
    else{
        return redirect('login');
    }
}


}
