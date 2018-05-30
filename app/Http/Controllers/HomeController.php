<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Job;
use App\JobResult;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idUser = Auth::user()->id;
        $listJob = Job::where('user_id', $idUser)->get();
        foreach ($listJob as $item){
            $resultJob[$item->id] = JobResult::where('job_id', $item->id)->get();
            $countResultJob[$item->id] = JobResult::where('job_id', $item->id)->get()->count();
        }
        return view('job',compact('listJob','resultJob', 'countResultJob'));
    }
}
