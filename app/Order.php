<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['userID', 'menuID', 'type', 'pack'];

    public $types = [
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
        $sum = DB::select("SELECT  menu.name, menu.id menuID, `order`.type, users.id userID, `order`.pack FROM `order`
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
                $order->name .= $this->types[$order->type];
            $userOrder[$order->userID]['menu'][] = $order->name;
            $userOrder[$order->userID]['id'][] = $order->menuID;
            $userOrder[$order->userID]['pack'] = $order->pack;
            $userOrder[$order->userID]['type'] = $order->type>0||(isset($userOrder[$order->userID]['type'])&&$userOrder[$order->userID]['type'])?1:0;
        }
        return $this->groupByOrder($userOrder);
    }

    public function groupByOrder($userOrder){
        $order = [];
        foreach ($userOrder as $user) {
            $menu = implode(',', $user['menu']);
            if(isset($order[$menu.$user['pack']]) && $order[$menu.$user['pack']]['pack'] == $user['pack']){
                $order[$menu.$user['pack']]['sum'] += 1;
            }else{
                $order[$menu.$user['pack']]['menu'] = implode(' + ', $user['menu']);
                $order[$menu.$user['pack']]['sum'] = 1;
                $order[$menu.$user['pack']]['type'] = $user['type'];
                $order[$menu.$user['pack']]['pack'] = $user['pack'];
            }
        }
        usort($order, function($a, $b) {
            return $b['sum'] - $a['sum'];
        });
        return $order;

    }
}
