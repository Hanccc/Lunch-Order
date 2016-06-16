<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/4/12
 * Time: 上午11:26
 */

namespace App\Services;


use App\Menu;
use App\Order;
use App\User;
use Auth;

class OrderService
{

    public static function createOrder($id, $type = 0, $pack = 0)
    {
        Order::create(['userID' => Auth::user()->id, 'menuID' => $id, 'type' => $type, 'pack' => $pack]);
        $menu = Menu::find($id);
        $menu->sum += 1;
        $menu->save();
    }

    public static function cancelOrder($requests)
    {
        $order = Order::find($requests->input('orderID'));
        if($order->pack == 1){
            if ($error = self::checkTakeoutTime())
                return redirect('/')->withErrors(['time' => $error]);
        }

        $user = User::find($order->userID);

        if (md5($user->id . $user->name) !== $requests->input('id'))
            abort(403, 'No Way To Cancel Other Lunch');

        $menu = Menu::find($order->menuID);
        $menu->sum -= 1;
        $menu->save();

        $order->delete();
    }

    public static function checkOrderTime()
    {
        $end = strtotime('11:45:00');
        $time = strtotime(date('H:i'));

        if ($time > $end)
            return '骚年，一切尘埃落定，为时已晚';
    }

    public static function checkTakeoutTime()
    {
        $end = strtotime('10:30:00');
        $time = strtotime(date('H:i'));

        if ($time > $end)
            return '骚年，一切尘埃落定，为时已晚';
    }



}