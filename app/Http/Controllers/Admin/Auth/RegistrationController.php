<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Model\Admin\Admin;
use App\Jobs\SendVerificationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
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
            'token' => 'required',
            'name' => 'required|regex:([A-Za-z ]+)|max:255',
            'gender' => 'required|regex:([0-1])',
            'phone' => 'required|string|max:11|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|captcha'
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
        $admin = Admin::where('email_token', base64_decode($data['token']))->first();
        return $admin->update([
            'name' => $data['name'],
            'gender' => $data['gender'] ? 'female':'male',
            'phone' => $data['phone'],
            'status' => 1,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showRegistrationForm($token)
    {
        $admin = Admin::where('email_token', base64_decode($token))->first();
        if ($admin) {
            return view('admin.user.auth.register')->with(['token' => $token]);
        }
        else return response(null, 400);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($admin = $this->create($request->all())));

        return redirect(route('admin.login'));
    }


    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
