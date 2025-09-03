<?php

namespace App\Http\Controllers\Auth;

use COM;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }  

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {  
             
                   return redirect()->intended('dashboard')
                    ->withSuccess('Welcome ' . ' ' . auth()->user()->username . '!');
        }

        return back()->withErrors(['email'=> 'Invalid Credentials'])->onlyInput('email');
        }
 

    public function dashboard()
    {
        if(Auth::check()){

            $Events = Event::where('date', '>', now())->with('User')->orderby('date','DESC')->get();
           
            return view('dashboard',compact('Events'));
        }   
        return redirect("login")->with('error','You are not allowed to access');
    }
     
    public function logout(Request $request) 
    {    
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
             
        return redirect ('login')->with('success', 'You have Successfully Logged Out!');
    }
}
