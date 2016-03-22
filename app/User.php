<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isUserExist($name, $email)
    {
        $user = User::where('name', $name)->where('email', $email)->first();

        if(!$user)
            return false;

        return $user;
    }

    public function login($user)
    {
        Auth::login($user);
    }
}
