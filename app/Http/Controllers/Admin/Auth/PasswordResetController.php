<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Model\Admin\Admin;
use App\Jobs\SendPasswordResetEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function showLinkRequestForm() {

        return view('admin.user.auth.passwords.email');

    }

    public function sendResetLinkEmail(Request $request) {

        $this->validate($request, [
            'email' => 'required|email|exists:admins',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $admin = Admin::where('email', $request['email'])->first();
        $token = Hash::make(Str::random(60) . $request['email']);

        DB::delete('DELETE FROM admin_password_resets WHERE email = ?', [$admin->email]);


        DB::insert('INSERT INTO admin_password_resets(email, token, created_at) VALUES(?, ?, ?)', [$admin->email, $token, new Carbon()]);

        dispatch(new SendPasswordResetEmail($admin, route('admin.password.reset', base64_encode($token))));

        session()->flash('status', 'An email with password reset link has been sent');

        return redirect()->back();
    }

    public function showResetForm($token) {

        $result = DB::select('SELECT * FROM admin_password_resets WHERE token = ?', [base64_decode($token)]);


        if (count($result) === 1 && Carbon::parse($result[0]->created_at)->addMinute(60) > (Carbon::now())) {
            return view('admin.user.auth.passwords.reset')->with(['token' => $token]);
        } else {
            return redirect()->home()->withErrors(['message' => 'Invalid/expire token. Please reset password again']);
        }



    }

    public function reset(Request $request) {

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $result = DB::select('SELECT * FROM admin_password_resets WHERE token = ?', [base64_decode($request['token'])]);

        if (count($result) === 1 && Carbon::parse($result[0]->created_at)->addMinute(60) > (Carbon::now())) {

            if ( $request['email'] === $result[0]->email) {

                DB::delete('DELETE FROM admin_password_resets WHERE email = ?', [$request['email']]);
                $admin = Admin::where('email', $request['email'])->first();
                $admin->update(['password' => Hash::make($request['password'])]);

                session()->flash('status', 'Password updated successfully.');

                return redirect(route('admin.login'));
            } else {
                return back()->withErrors(['email' => 'Invalid user email']);
            }
        } else {
            return back()->withErrors(['token' => 'Invalid/expire token. Please reset password again']);
        }

    }
}
