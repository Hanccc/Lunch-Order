<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Menu;
use App\Order;
use App\Services\OrderService;
use App\User;
use Auth;
use DB;
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
        $menu = Menu::where('status', 1)->orderBy('sum', 'desc')->orderBy('price', 'desc')->get();
        $sum = (new Order)->getTotal();
        $order = Order::where('created_at', 'like', date('Y-m-d', time()) . '%')->orderBy('created_at', 'desc')->get();
        return view('home', ['menus' => $menu, 'orders' => $order, 'userID' => Auth::user()->id, 'sum' => $sum]);
    }

    public function order($id, $type = 0)
    {
        if ($error = OrderService::checkOrderTime())
            return redirect('/')->withErrors(['time' => $error]);

        OrderService::createOrder($id, $type);

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
