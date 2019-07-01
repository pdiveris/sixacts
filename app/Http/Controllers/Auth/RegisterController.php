<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendEmailJob;
use App\Mail\VariableUserEmail as UserEmail;
use App\User;
use App\Http\Controllers\Controller;
use App\VerifyUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\VerifyMail;
use Illuminate\Http\Request as Request;

class RegisterController extends Controller
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        $token = sha1(time());
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        
        $email = new VerifyMail($user, $token);
        $dispatcher = new SendEmailJob($email);
        dispatch($dispatcher);
        
        return $user;
    }
    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    /**
     * Should be called upon registration completion
     * Part of custom verification email infrastructure
     *
     * @see https://codebriefly.com/custom-user-email-verification-activation-laravel/
     *
     * @param \Illuminate\Http\Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        return redirect('/login')->with('status', 'We sent you an activation code. Check your email and click on the link to verify.');
    }
    
    /**
     * Verify the user.
     *
     * Send an email upon successful verification.
     *
     * @author Petros Diveris <petros@bentleysoft.com>
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $user->verified = 1;
                $user->save();
                
                $status = "Your e-mail is verified. You can now login.";
    
                $profileUrl = url('user/profile');
                $email = new UserEmail($user, 'user_welcome', ['profileUrl'=>$profileUrl]);
    
                $dispatchJob = new SendEmailJob($email);
                dispatch($dispatchJob);
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } else {
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }
        return redirect('/login')->with('status', $status);
    }
}
