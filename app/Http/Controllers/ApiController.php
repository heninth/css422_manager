<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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

    }
}
