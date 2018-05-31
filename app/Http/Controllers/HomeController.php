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
            $countResultJob[$item->id] = JobResult::where('job_id', $item->id)->get()->count();
        }
        return view('job',compact('listJob', 'countResultJob'));
    }
    public function show($job_id)
    {
        $job = Job::where('id', $job_id)->first();
        $listResult = JobResult::where('job_id', $job_id)->get();
        $countResult = JobResult::where('job_id', $job_id)->get()->count();
        return view('result-job', compact('listResult', 'countResult', 'job'));
    }
    public function add()
    {
        return view('add-job');
    }
    public function delete($job_id)
    {

    }
}
