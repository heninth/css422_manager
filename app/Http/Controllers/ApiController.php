<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Worker;

class ApiController extends Controller
{
    public function workerRegistration() {
        $worker = new Worker();
        $worker->save();

        return json_encode(['workerId' => $worker->id]);
    }
}
