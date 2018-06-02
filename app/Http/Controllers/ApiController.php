<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Job;
use App\JobResult;
use App\Task;
use App\Worker;

class ApiController extends Controller
{
    public function workerRegistration() {
        $token = '';
        $length = 20;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        while(true) {
            for ($i = 0; $i < $length; $i++) {
                $token .= $characters[rand(0, $charactersLength - 1)];
            }

            if (Worker::where('token', $token)->first() == null) {
                break;
            }
        }


        $worker = new Worker();
        $worker->update = Carbon::now('Asia/Bangkok');
        $worker->token = $token;
        $worker->save();

        return json_encode(['workerToken' => $worker->token]);
    }

    /*
    public function workerOnline(Request $request) {
        $worker = Worker::where('token', $request->input('workerToken', ''))->first();
        if ($worker == null) {
            return json_encode(['success' => false]);
        }

        $worker->update = Carbon::now('Asia/Bangkok');
        $worker->status = 'online';
        $worker->save();
        return json_encode(['success' => true]);
    }

    public function workerOffline(Request $request) {
        $worker = Worker::where('token', $request->input('workerToken', ''))->first();
        if ($worker == null) {
            return json_encode(['success' => false]);
        }

        $worker->update = Carbon::now('Asia/Bangkok');
        $worker->status = 'offline';
        $worker->save();
        return json_encode(['success' => true]);
    }
    */

    public function getTask(Request $request){
       
      $worker = Worker::where('token', $request->input('workerToken', ''))->first();
      if ($worker == null) {
          return json_encode(['success' => false]);
      }
      //update worker time
      $worker->update_active();

      //delete inactive worker
      Worker::delete_inactive();

      $jobs = Job::where('status','running')->get(); // look in the job that running
      $newTask = false;
      if($jobs->isEmpty()){
          return json_encode(['newTask' => $newTask]);
      }
      foreach ($jobs as $job) {
        $flag = 0; //check if we got task in sub loop already ?
        $tasks = Task::where([['status','!=','finished'],['job_id',$job->id]])->get();
        foreach ($tasks as $task) {//check all the task that running or queue if have some of them already timeout let get it start again
            if($task->status == 'running'){
              if($task->timeout_at < Carbon::now('Asia/Bangkok') ){
                $newTask = true;
                $taskId = $task->id;
                $algo = $job->algorithm;
                $start = $task->start_hash;
                $end = $task->end_hash;
                $range = $task->range;
                $timeout = 300;
                $hashes = JobResult::select('hash')->where('job_id',$job->id)->get();
                $flag = 1;
                $task->timeout_at = Carbon::now('Asia/Bangkok')->addMinutes(5);
                $task->save();
                break;
              }
            }
            elseif($task->status == 'queue') {
              $newTask = true;
              $taskId = $task->id;
              $algo = $job->algorithm;
              $start = $task->start_hash;
              $end = $task->end_hash;
              $range = $task->range;
              $timeout = 300;
              $hashes = JobResult::select('hash')->where('job_id',$job->id)->get();
              $flag = 1;
              $task->timeout_at = Carbon::now('Asia/Bangkok')->addMinutes(5);
              $task->status = 'running';
              $task->save();
              break;
            }
        }
        if($flag == 1){
            break;
        }//inner loop
      }//1stloop

    return json_encode([
                      'newTask' => $newTask ,
                      'taskId' => $taskId ,
                      'algo' => $algo ,
                      'start' => $start ,
                      'end' => $end ,
                      'range' => $range ,
                      'timeout' => $timeout ,
                      'hashes' => $hashes ,
                      ]);

    }

    public function submitTask(Request $request){
      $worker = Worker::where('token', $request->input('workerToken', ''))->first();
      if ($worker == null) {
          return json_encode(['success' => false]);
      }
      //update worker time
      $worker->update_active();

      $taskId = $request->taskId;
      $answerBool = $request->answer;
      $task = Task::where('id',$taskId)->first();
      $jobid = $task->job_id;
      $task->status = 'finished';
      $task->save();
      if($answerBool == 'true'){
        $hashes = $request->hashes;
        $plains = $request->plains;
        $index = 0;
        foreach($hashes as $hash){
          $dbhash = Jobresult::where([ ['job_id',$jobid] , ['hash',$hash] ])->first();
          $dbhash->plain = $plains[$index];
          $dbhash->save();
          $index++;
        }
        // check if job finished ?
        if( (Jobresult::where([ ['job_id',$jobid] , ['plain','' ] ])->get() )->isEmpty() ){
          $job = Job::where('id',$jobid)->first();
          $job->status = 'finished';
          $job->save();
        }
      }

      return json_encode(['success' => true]);
    }

}
