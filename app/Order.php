<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['userID', 'menuID', 'type'];

    public $type = [
        1 => '面',
        2 => '河粉',
        3 => '米粉',
    ];

    public function menu(){
        return $this->hasOne('App\Menu', 'id', 'menuID');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'userID');
    }

    public function getTotal(){
        $today = date('Y-m-d', time());
        $sum = DB::select("SELECT  menu.name, menu.id menuID, `order`.type, users.id userID FROM `order`
            INNER JOIN users ON users.id = order.userID
            INNER JOIN menu ON menu.id = order.menuID WHERE `order`.created_at LIKE '{$today}%'
            GROUP BY `order`.id, `order`.type ORDER BY userID, menu.id DESC");
        $userOrder = $this->groupByUser($sum);
        return $userOrder;
    }

    public function groupByUser($sum){
        $userOrder = [];
        foreach ($sum as $order) {
            if($order->type != 0)
                $order->name .= $this->type[$order->type];
            $userOrder[$order->userID]['menu'][] = $order->name;
            $userOrder[$order->userID]['id'][] = $order->menuID;
        }
        return $this->groupByOrder($userOrder);
    }

    public function groupByOrder($userOrder){
        $order = [];
        foreach ($userOrder as $user) {
            $id = implode(',', $user['id']);
            if(isset($order[$id])){
                $order[$id]['sum'] += 1;
            }else{
                $order[$id]['menu'] = implode(' + ', $user['menu']);
                $order[$id]['sum'] = 1;
            }
        }
        usort($order, function($a, $b) {
            return $b['sum'] - $a['sum'];
        });
        return $order;

    }
}
