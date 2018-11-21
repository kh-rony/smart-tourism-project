<?php

namespace App\Http\Controllers\User\Auth;

use App\Jobs\SendVerificationEmail;
use App\Model\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|regex:([A-Za-z. ]+)|max:255',
            'last_name' => 'required|regex:([A-Za-z. ]+)|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required',
            'phone' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_token' => Hash::make($data['email'] . Str::random(60)),
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'occupation' => $data['occupation']
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        dispatch(new SendVerificationEmail($user, route('register.verify', base64_encode($user->email_token))));

        return response()->json(['message' => 'You have registered successfully. An email is sent to you for verification.'], 201);
    }

    public function verification($token)
    {
        $user = User::where('email_token', base64_decode($token))->first();

        if($user) {
            $user->verified = 1;
            $user->save();
            $message = "Hello {$user->first_name}, your email is verified successfully. Please login now";
        }
        else $message = 'Invalid token';

        return redirect()->home()->with('message', $message);
    }
}
