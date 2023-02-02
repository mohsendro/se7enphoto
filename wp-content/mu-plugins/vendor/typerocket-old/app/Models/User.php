<?php
namespace App\Models;

use TypeRocket\Models\WPUser;

class User extends WPUser
{
    public function flights()
    {
        return $this->hasMany(Flight::class, 'customer_id');
    }

    /**
    * Get the resume record associated with the user.
    */
    public function resume() {
        return $this->hasOne(Resume::class, 'user_id');
        // return $this->hasOne('\App\Models\Resume', 'user_id');
    }

    /**
    * Get the tojob record associated with the user.
    */
    public function tojob() {
        return $this->hasMany(ToJob::class, 'user_id');
        // return $this->hasOne('\App\Models\ToJob', 'user_id');
    }

}