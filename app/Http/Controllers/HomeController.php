<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Menu;
use App\Order;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $user;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = Menu::orderBy('sum', 'desc')->orderBy('price', 'desc')->get();
        $sum = (new Order)->getTotal();
        $order = Order::where('created_at', 'like', date('Y-m-d', time()) . '%')->orderBy('created_at', 'desc')->get();
        return view('home', ['menus' => $menu, 'orders' => $order, 'userID' => $this->user->id, 'sum' => $sum]);
    }

    public function addMenu($price, $name, $type = null)
    {
        if ($type)
            Menu::create(['name' => $name, 'price' => $price, 'type' => $type]);
        else
            Menu::create(['name' => $name, 'price' => $price]);
    }

    public function order($id, $type = 0)
    {
        if ($error = $this->checkTime())
            return redirect('/')->withErrors(['time' => $error]);
        Order::create(['userID' => $this->user->id, 'menuID' => $id, 'type' => $type]);
        $menu = Menu::find($id);
        $menu->sum += 1;
        $menu->save();
        return redirect('/');
    }

    public function cancel(Request $requests)
    {
        if ($error = $this->checkTime())
            return redirect('/')->withErrors(['time' => $error]);
        $order = Order::find($requests->input('orderID'));
        $user = User::find($order->userID);

        if (md5($user->id . $user->name) !== $requests->input('id'))
            abort(403, 'No Way To Cancel Other Lunch');

        $menu = Menu::find($order->menuID);
        $menu->sum -= 1;
        $menu->save();

        $order->delete();
        return redirect('/');
    }

    private function checkTime()
    {
        $begin = strtotime('10:00:00');
        $end = strtotime('11:45:00');
        $time = strtotime(date('H:i'));

        if ($time < $begin)
            return '骚年，没到点呢，先认真工作';

        if ($time > $end)
            return '骚年，一切尘埃落定，为时已晚';
    }
}
