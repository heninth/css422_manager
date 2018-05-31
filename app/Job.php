<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
  public function jobResult() {
      return $this->hasMany('App\JobResult');
  }
}
