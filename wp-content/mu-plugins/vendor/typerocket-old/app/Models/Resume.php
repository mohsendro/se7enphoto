<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Resume extends Model
{
    protected $resource = 'resumes';

    /**
    * Get the user record associated with the resume.
    */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
        // return $this->belongsTo('\App\Models\User', 'user_id');
    }

}