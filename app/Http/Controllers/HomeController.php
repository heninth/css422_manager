<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Job;
use App\JobResult;
use App\Task;
use App\Worker;
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
            $r = JobResult::where('job_id', $item->id)->get();
            $countCrackHash[$item->id] = $r->where('plain', '!=', '')->count();
            $countUnCrackHash[$item->id] = $r->count();
        }
        return view('job',compact('listJob', 'countCrackHash', 'countUnCrackHash'));
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

        // แตก task ตรงนี้
        for($i = $request->input('min_length') ; $i<= $request->input('max_length') ; $i++ ){
          $tasknumber = count(Worker::where('status','online')->get()) * 2;
          if($tasknumber == 0 || $tasknumber < 10){
            $tasknumber = 10;
          }
          $testcase = floor(pow(62,$i) / $tasknumber);
          for($y = 1 ; $y <= $tasknumber ; $y++){
            $task = new Task();
            $task->job_id = Job::all()->last()->id;
            $task->range = $i;
            $task->start_hash = ($y-1) * $testcase;
            if($y != $tasknumber){
              $task->end_hash = ($y * $testcase) - 1 ;
            }
            else{
              $task->end_hash = pow(62,$i) - 1;
            }
            $task->save();
          }
        }



        //--------------------------------------refresh job page------------------------------------------------//

       return redirect()->action('HomeController@index');
    }

    public function delete($job_id)
    {
        JobResult::where('job_id', $job_id)->delete();
        Task::where('job_id', $job_id)->delete();
        Job::where('id', $job_id)->delete();
        //--------------------------------------refresh job page------------------------------------------------//
        return redirect()->action('HomeController@index');
    }
}
