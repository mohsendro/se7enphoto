<?php
namespace App\Models;

use TypeRocket\Models\Model;

class ToJob extends Model
{
    protected $resource = 'to_jobs';

    /**
    * Get the job record associated with the tojob.
    */
    public function job() {
        return $this->belongsTo(Job::class, 'job_id');
        // return $this->belongsTo('\App\Models\Job', 'job_id');
    }

    /**
    * Get the user record associated with the tojob.
    */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
        // return $this->belongsTo('\App\Models\User', 'user_id');
    }
}