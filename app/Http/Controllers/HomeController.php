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
        // TODO: crack ยังโชว์ผิดอยู่ ต้องโชว์ตัวที่มี plain text แล้ว
        // อาจจะโชว์เป็น 0/10 ก็ได้เป็น จำนวนที่มี plain text แล้ว / จำนวนทั้งหมด
        // เรียงลำดับต้องเอาตัวใหม่มาก่อนรึเปล่า
        $idUser = Auth::user()->id;
        $listJob = Job::where('user_id', $idUser)->orderBy('id', 'desc')->get();
        foreach ($listJob as $item){
            $countResultJob[$item->id] = JobResult::where('job_id', $item->id)->where('plain', '!=', '')->get()->count();
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

    public function save(Request $request)
    {
        $request->validate([
            'algorithm' => 'required',
            'min_length' => 'required',
            'max_length' => 'required',
            'hash' => 'required'
        ]);
        $job = new Job();
        $job->user_id = Auth::user()->id;
        $job->algorithm = $request->input('algorithm');
        $job->min_length = $request->input('min_length');
        $job->max_length = $request->input('max_length');
        $array_hash =  explode("\r\n", $request->input('hash'));
        
        // TODO: เช็คตัวซ้ำ
        $duplicate = count(array_unique($array_hash));
        if (count($array_hash) != $duplicate) {
            return back()->with('duplicate', 'Please check duplicate value in hash');
        }
        $hash_length = ($job->algorithm == 'md5') ? 32 : 40;
        for($i = 0; $i < count($array_hash); $i++ ){
            if (strlen($array_hash[$i]) != $hash_length){
                return back()->with('error', 'Please check length of hash');
            }
        }
        $job->save();
        for($i = 0; $i < count($array_hash); $i++){
            $job_Result = new JobResult();
            $job_Result->job_id = Job::all()->last()->id;
            $job_Result->hash = $array_hash[$i];
            $job_Result->plain = '';
            $job_Result->save();
        }

        // TODO: แตก task ตรงนี้

        //--------------------------------------refresh job page------------------------------------------------//

       return redirect()->action('HomeController@index');
    }

    public function delete($job_id)
    {
        JobResult::where('job_id', $job_id)->delete();
        Job::where('id', $job_id)->delete();
        //--------------------------------------refresh job page------------------------------------------------//
        return redirect()->action('HomeController@index');
    }
}
