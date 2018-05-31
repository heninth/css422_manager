<?php

namespace App;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;


class JobResult extends Model
{
    use HasCompositePrimaryKey;
    protected $primaryKey = ['job_id', 'hash',];
  //  public $incrementing = false;

    public function job() {
      return $this->belongsTo('App\Job');
    }
}
