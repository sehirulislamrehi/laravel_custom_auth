<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    
    public function login_view(){
        return view('login');
    }
    public function register_view(){
        return view('register');
    }
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if( $user->save() ){
            return redirect()->route('login.view');
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:6',
        ]);
        $credentials = $request->only('email','password');
        if( Auth::attempt($credentials, true) ) {
            return redirect()->route('dashboard');
        }
        else{
            $request->session()->flash('login_failed','Invalid Credentials');
            return redirect()->route('login.view');
        }
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login.view');
    }


    public function getEmail(){
        return view('password_email');
    }

    public function postEmail(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        $token = Str::random(60);
        DB::table('password_resets')->insert(
            ['email'=>$request->email,'token' => $token, 'created_at' => Carbon::now()]
        );
        $email = $request->email;
        Mail::send('verify',['token' => $token, 'email' => $email], function($message) use ($request){
            $message->from('mdsehirulislamrehi@gmail.com');
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });
        $request->session()->flash('send','We send you a password reset link in your email address');
        return redirect()->route('get.email');
    }

    public function getPassword($token, $email){
        return view('reset',['token' => $token, 'email' => $email]);
    }
}
