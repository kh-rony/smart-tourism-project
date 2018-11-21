<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use JWTAuth;

class SocialAuthController extends Controller
{
    protected $redirectTo = '/';

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param $provider
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @param $provider
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $user = $this->findOrCreateUser($user, $provider);

        $response['access_token'] = $user->email;
        $response['verified'] = 0;
        if ($user->verified) {
            $response = $this->login($user);
            $response['verified'] = "";
        }

        return redirect()->home()
            ->with('access_token', $response['access_token'])
            ->with('verified', $response['verified']);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('email', $user->getEmail())->first();
        if ($authUser) {
            return $authUser;
        }

        $newUser =  User::create([
            'first_name'     => $user->getName(),
            'last_name'     => $user->getNickname(),
            'email'    => $user->getEmail(),
            'provider' => $provider,
            'provider_id' => $user->getId()
        ]);

        return $newUser;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param User $user
     * @return array
     */
    protected function login(User $user)
    {
        if (! $token = JWTAuth::fromUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function register(Request $request, $email)
    {
        $this->validate($request, [
            'first_name' => 'required|regex:([A-Za-z. ]+)|max:255',
            'last_name' => 'required|regex:([A-Za-z. ]+)|max:255',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required',
            'phone' => 'required'
        ]);

        $user = User::whereEmail($email)->first();

        if ($user) {
            $user->update([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email_token' => Hash::make($email . Str::random(60)),
                'password' => Hash::make($request['password']),
                'gender' => $request['gender'],
                'phone' => $request['phone'],
                'occupation' => $request['occupation'],
            ]);
            $user->verified = 1;
            $user->save();

            return response()->json(['message' => "Hello {$user->first_name}, your registration is completed successfully"], 201);
        } return response()->json(['message' => 'Bad request'], 400);

    }
}
