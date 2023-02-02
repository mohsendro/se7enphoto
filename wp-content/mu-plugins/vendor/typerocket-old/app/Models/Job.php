<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Job extends Model
{
    protected $resource = 'jobs';

    /**
    * Get the company record associated with the job.
    */
    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
        // return $this->belongsTo('\App\Models\Company', 'company_id');
    }

    /**
    * Get the tojob record associated with the company.
    */
    public function tojob() {
        return $this->hasMany(ToJob::class, 'job_id');
        // return $this->hasOne('\App\Models\Job', 'company_id');
    }


}