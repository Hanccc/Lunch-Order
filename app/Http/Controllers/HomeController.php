<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Menu;
use App\Order;
use App\Services\OrderService;
use App\User;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Collection $menu */
        $menu = Menu::where('status', 1)->orderBy('sum', 'desc')->orderBy('price', 'desc')->get();
        $menu = $menu->sortByDesc(function($menu){
           return $menu['sum'] * ( $menu['price'] / 20);
        });
        $sum = (new Order)->getTotal();
        $order = Order::where('created_at', 'like', date('Y-m-d', time()) . '%')->orderBy('created_at', 'desc')->get();
        return view('home', ['menus' => $menu, 'orders' => $order, 'userID' => Auth::user()->id, 'sum' => $sum]);
    }

    public function order($id, $type = 0, $pack = 0)
    {
        if($pack == 0){
            if ($error = OrderService::checkOrderTime())
                return redirect('/')->withErrors(['time' => $error]);
        }
//        if($pack == 1){
//            if ($error = OrderService::checkTakeoutTime())
//                return redirect('/')->withErrors(['time' => $error]);
//        }

        OrderService::createOrder($id, $type, $pack);

        return redirect('/');
    }

    public function cancel(Request $requests)
    {
        if ($error = OrderService::checkOrderTime())
            return redirect('/')->withErrors(['time' => $error]);

        OrderService::cancelOrder($requests);

        return redirect('/');
    }
}
