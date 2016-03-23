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

    public function loginAsTest(){
        $name = 'test1';
        $email = 'test1@test.com';
        if(!$user = (new User())->isUserExist($name, $email))
            $user = User::create(['name' => $name, 'email' => $email]);

        $this->login($user);
    }
}
