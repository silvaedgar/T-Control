<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $exist_client = false;
        $verify_client = User::with('userclient')->role('Client')->where('id',auth()->user()->id)->get();
        if (count($verify_client) > 0) {
            if  (count($verify_client[0]->UserClient) > 0)
                $exist_client = true;
        }
        return view('home-auth',compact('exist_client'));
    }


    public function homeguest()
    {
        $images = ['refrescos.jpeg','harina de maiz.jpeg','speedmax.jpeg','cafe.jpeg','ricota.jpeg'];
        return view('home-guest',compact('images'));
    }

    public function hometcontrol()
    {
        return view('home-tcontrol');
    }

    public function whoaim()
    {
        return view('whoaim');
    }

    public function welcome()
    {
        $images = ['refrescos.jpeg','harina de maiz.jpeg','speedmax.jpeg','cafe.jpeg','ricota.jpeg'];
        return view('home-guest',compact('images'));
    }

}
