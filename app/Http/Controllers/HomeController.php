<?php

namespace App\Http\Controllers;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = Project::with('userName')->where('user_id',Auth::id())->get();
        // dd($projects);
        return view('Project.Index',['projects' => $projects]);
        // return view('Project.Index');
    }
}
