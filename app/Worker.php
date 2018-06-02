<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Worker extends Model
{
    public function update_active() {
        //update worker time
        $worker->update = Carbon::now('Asia/Bangkok');
        $worker->status = 'online';
        $worker->save();
    }

    public static function delete_inactive() {
        Worker::whereDate('update', '<', Carbon::now('Asia/Bangkok')->subMinutes(15))->delete();
    }
}
