<?php

namespace App\Http\Controllers\User\Auth;

use App\Jobs\SendPasswordResetEmail;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users'
        ]);

        $user = User::where('email', $request['email'])->first();
        $token = Hash::make(Str::random(60) . $request['email']);

        DB::delete('DELETE FROM password_resets WHERE email = ?', [$user->email]);


        DB::insert('INSERT INTO password_resets(email, token, created_at) VALUES(?, ?, ?)', [$user->email, $token, new Carbon()]);

        dispatch(new SendPasswordResetEmail($user, route('password.reset', base64_encode($token))));

        return response()->json(['message' => 'An email with password reset link has been sent']);
    }

    public function showResetForm($token)
    {
        $result = DB::select('SELECT * FROM password_resets WHERE token = ?', [base64_decode($token)]);

        if (count($result) === 1 && Carbon::parse($result[0]->created_at)->addMinute(60) > (Carbon::now()))
            return redirect()->home()->with('reset_token', $token);
        else
            return redirect()->home()->with('message', 'Invalid/expire token. Please reset password again');
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $result = DB::select('SELECT * FROM password_resets WHERE token = ?', [base64_decode($request['token'])]);

        if (count($result) === 1 && Carbon::parse($result[0]->created_at)->addMinute(60) > (Carbon::now())) {
            if ($request['email'] === $result[0]->email) {
                DB::delete('DELETE FROM password_resets WHERE email = ?', [$request['email']]);
                $user = User::where('email', $request['email'])->first();
                $user->update(['password' => Hash::make($request['password'])]);

                return response()->json(['message' => 'You have reset password successfully. Please login now']);
            } else
                return response()->json(['message' => 'Invalid user email']);

        } else
            return response()->json(['message' => 'Invalid/expire token. Please reset password again']);
    }
}
