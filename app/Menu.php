<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = ['name', 'price', 'type'];

    public function order(){
        return $this->belongsTo('App\Order');
    }

}
