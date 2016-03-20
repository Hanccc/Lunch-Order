<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function showLoginForm()
    {
        $view = property_exists($this, 'loginView')
            ? $this->loginView : 'auth.authenticate';

        if (view()->exists($view)) {
            return view($view);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');

        if($this->isUserExist($name, $email))
            return redirect('/');

        if(!$this->loginWithOpenApi($name, $email))
            return $this->sendFailedLoginResponse($request);

        $user = User::create(['name' => $name, 'email' => $email]);
        Auth::login($user);

        return redirect('/');
    }

    public function isUserExist($name, $email)
    {
        $user = User::where('name', $name)->where('email', $email)->first();

        if(!$user)
            return false;

        Auth::login($user);

        return true;
    }

    public function loginWithOpenApi($name, $email)
    {
        $token = $this->getExmailToken();

        return $this->exmailValidate($name, $email, $token);
    }

    private function exmailValidate($name, $email, $token)
    {
        $client = new Client(['verify' => false]);

        $response = $client->request('POST', 'http://openapi.exmail.qq.com:12211/openapi/user/get', [
            'query' => [
                'alias' => $email,
                'access_token' => $token,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        return (property_exists($response, 'Name') && $response->Name === $name)?true:false;
    }

    private function getExmailToken()
    {
        $client = new Client(['verify' => false]);

        $response = $client->request('POST', 'https://exmail.qq.com/cgi-bin/token', [
            'query' => [
                'client_id' => config('services.exmail.client_id'),
                'client_secret' => config('services.exmail.client_secret'),
                'grant_type' => 'client_credentials',
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        return $response->access_token;
    }

    public function logout()
    {
        Auth::guard()->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withErrors([
                'email' => 'Authentication Fail',
            ]);
    }

}
