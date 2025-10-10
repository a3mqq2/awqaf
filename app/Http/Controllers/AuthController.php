<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SystemLog;

class AuthController extends Controller
{
  
    public function login()
    {
        return view('auth.login');
    }

   
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            // إضافة سجل في system_logs
            SystemLog::create([
                'description' => 'قام المستخدم بتسجيل الدخول بنجاح',
                'user_id'     => Auth::id(),
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'مرحباً بك، تم تسجيل الدخول بنجاح');
        }

        return redirect()->back()
            ->withInput($request->except('password'))
            ->with('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            SystemLog::create([
                'description' => 'قام المستخدم بتسجيل الخروج',
                'user_id'     => Auth::id(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
