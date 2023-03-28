<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use App\Models\UserPackage;


class UserDashboardController extends Controller

{
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|different:old_password|confirmed',
        ]);
        $user = User::find(Auth::id());
        $hashedPassword = $user->password;

        if (Hash::check($request->old_password, $hashedPassword)) {
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            return redirect()->back()->with('success', 'password updated successfully.');
        } else {
            return redirect()->back()->withErrors(['password_error' => 'The old password does not match.']);
        }
    }

    public function dashboardDisplay()
    {


    }

    public function displayAvailablePackages()
    {
        $data = Package::all()->toArray();
        @dd($data[0]['id'],$data[0]['name'],$data[0]['price'],$data[0]['qty'],$data[0]['duration']);
    }

    public function displayActivePackages()
    {
        $data = UserPackage::where('status',1)->get()->toArray();
        // @dd($data);
        @dd($data[0]['id'],$data[0]['user_id'],$data[0]['package_id'],$data[0]['investment'],$data[0]['profit_per_kg'],$data[0]['user_profit']);
    }

    public function displayRequestedPackages()
    {
        $data = UserPackage::where('status',0)->get()->toArray();
        @dd($data[0]['id'],$data[0]['user_id'],$data[0]['package_id'],$data[0]['investment'],$data[0]['profit_per_kg'],$data[0]['user_profit']);
    }






}
