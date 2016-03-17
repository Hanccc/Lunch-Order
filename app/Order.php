<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['userID', 'menuID'];

    public function menu(){
        return $this->hasOne('App\Menu', 'id', 'menuID');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'userID');
    }
}
