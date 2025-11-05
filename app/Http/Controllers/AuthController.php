<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login(){
        return view('auth/login');

    }

     public function loginProses(Request $request){
        $request ->validate([
            'email'     => 'required',
            'password'  => 'required|min:8',
        ],[
            'password.min' => 'Password Minimal 8 Karakter'
        ]);

        $data = array(
            'email'     => $request->email,
            'password'  => $request->password,
        );
        if(Auth::attempt($data)){
            return redirect()->route('dashboard')->with('success','Anda Berhasil Login');
        }else{
            return redirect()->back()->with('error','Username dan Password Salah');
        }
     }

     public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('success','Anda Berhasil Logout');
    }
}
