<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userName', 'password', 'firstName', 'lastName', 'dateOfBirth'
    ];


    public function groups(){
        return $this->belongsToMany(Groups::class, 'groups_has_users', 'users_id',
            'groups_id')->withTimestamps();
    }
}
