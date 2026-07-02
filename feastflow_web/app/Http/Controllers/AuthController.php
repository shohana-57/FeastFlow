<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
   
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = DB::select(
            'SELECT * FROM users WHERE email = ? AND password = ?',
            [$email, $password]
        );

        if (count($user) > 0) {
            // Login Successful
            session(['user_id' => $user[0]->id]);
            session(['user_name' => $user[0]->name]);
            session(['user_role' => $user[0]->role]);

            return redirect('/menu')->with('success', 'Welcome ' . $user[0]->name . '!');
        } else {
            return back()->with('error', 'Invalid email or password!');
        }
    }

  
    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
