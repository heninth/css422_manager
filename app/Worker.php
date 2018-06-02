<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Worker extends Model
{
    public function update_active() {
        //update worker time
        $this->update = Carbon::now('Asia/Bangkok');
        $this->status = 'online';
        $this->save();
    }

    public static function delete_inactive() {
        Worker::where('update', '<', Carbon::now('Asia/Bangkok')->subMinutes(15))->delete();
    }
}
