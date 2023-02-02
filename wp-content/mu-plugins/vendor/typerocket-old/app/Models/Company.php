<?php
namespace App\Models;

use TypeRocket\Models\Model;
// use App\Models\Job;

class Company extends Model
{
    protected $resource = 'companies';

    /**
    * Get the job record associated with the company.
    */
    public function job() {
        // return $this->hasOne(Job::class, 'company_id');
        // return $this->hasOne('\App\Models\Job', 'company_id');
        return $this->hasMany('\App\Models\Job', 'company_id');
    }

}