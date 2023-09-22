<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

use Illuminate\Support\Facades\Log; 
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        Log::info('Incoming Login Request Data:', $request->all());
    
        try {
            // Validate the incoming request data
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if (Auth::attempt($credentials)) {
                // Authentication was successful
                $user = Auth::user();
    
                // Create a personal access token for the user
                $token = $user->createToken('Personal Access Token')->accessToken;
    
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'access_token' => $token,
                ]);
            }
    
            // Authentication failed
            throw new \Exception('Invalid email or password');
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error in login method: ' . $e->getMessage());
    
            // Return an error response to the client
            return response()->json(['message' => 'Error logging in'], 401);
        }
    }
    
}
