<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecoveryPasswordRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Score;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('home.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->status_level = 0;
        $user->password = \Hash::make($request->password);
        $user->save();

        $scores = new Score;
        $scores->user_id = $user->id;
        $scores->value = 0;
        $scores->save();

        \Mail::send('emails.new_user', ['user' => $user], function($message) use ($user)
        {
            $message->to("airtonmfilho@hotmail.com", $user->name)->subject(trans('emails.new_user_title'));
        });

        \Auth::attempt(array('email' => $request->email, 'password' => $request->password));

        return redirect()->intended('/profile');
    }

    /**
     * Redirect to donate page
     *
     * @return View
     */
    public function donate()
    {
        return view('home.donate');
    }

    /**
     * Login
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        if (\Auth::attempt(array('email' => $request->email, 'password' => $request->password)))
        {
            return redirect()->intended('/profile');
        } else{
            return \Redirect::back();
        }
    }

    /**
     * Redirect to recovery password page
     *
     * @return View
     */
    public function forgotPass()
    {
        return view('home.forgot_password');
    }

    /**
     * Send email with new password to user
     *
     * @param  Request  $request
     * @return Mix
     */
    public function recoveryPassword(RecoveryPasswordRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->get();
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr( str_shuffle( $chars ), 0, 8);
        \Mail::send('emails.temp_password', ['user' => $user, 'password' => $password], function($message) use ($user, $password)
        {
            foreach($user AS $id=>$key){
                $message->to($key->email, $key->name)->subject(trans('home.temp_password_mail_title'));
                $edit_user = User::find($key->id);
                $edit_user->password = \Hash::make($password);
                $edit_user->save();
            }
        });
        return \Redirect::route('home.index');
    }

    /**
     * Login
     *
     * @return Response
     */
    public function mail()
    {
        return view('emails.temp_password')->with('password', '12345678');
    }
}
