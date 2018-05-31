<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Worker;
use App\Job;
use App\JobResult;
use App\Task;

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
        $worker->token = $token;
        $worker->save();

        return json_encode(['workerToken' => $worker->token]);
    }

    public function workerOnline(Request $request) {
        $worker = Worker::where('token', $request->input('workerToken', ''))->first();
        if ($worker == null) {
            return json_encode(['success' => false]);
        }

        $worker->update = Carbon::now();
        $worker->status = 'online';
        $worker->save();
        return json_encode(['success' => true]);
    }

    public function workerOffline(Request $request) {
        $worker = Worker::where('token', $request->input('workerToken', ''))->first();
        if ($worker == null) {
            return json_encode(['success' => false]);
        }

        $worker->update = Carbon::now();
        $worker->status = 'offline';
        $worker->save();
        return json_encode(['success' => true]);
    }

    public function getTask(Request $request){
      $worker = Worker::where('token', $request->input('workerToken', ''))->first();
      if ($worker == null) {
          return json_encode(['success' => false]);
      }

      $jobs = Job::where('status','running')->get(); // look in the job that running
      $newTask = false;
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
                $timeout = 120;
                $hashes = JobResult::select('hash')->where('job_id',$job->id)->get();
                $flag = 1;
                $task->timeout_at = Carbon::now('Asia/Bangkok')->addMinutes(2);
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
              $timeout = 120;
              $hashes = JobResult::select('hash')->where('job_id',$job->id)->get();
              $flag = 1;
              $task->timeout_at = Carbon::now('Asia/Bangkok')->addMinutes(2);
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

}
