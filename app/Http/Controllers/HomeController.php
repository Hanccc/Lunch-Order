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
        $today =  date('Y-m-d', time());
        $menu = Menu::all();
        $sum = DB::select("SELECT count(*) AS total, menu.name FROM `order`
            INNER JOIN menu ON menu.id = order.menuID WHERE `order`.created_at LIKE \"{$today}%\"
            GROUP BY menuID ORDER BY count(*) DESC");
        $order = Order::where('created_at', 'like', date('Y-m-d', time()).'%')->orderBy('created_at', 'desc')->get();
        return view('home', ['menus' => $menu, 'orders' => $order, 'userID' => $this->user->id, 'sum' => $sum]);
    }

    public function addMenu($price, $name){
        Menu::create(['name' => $name, 'price' => $price]);
    }

    public function order($id){
        Order::create(['userID' => $this->user->id, 'menuID' => $id]);
        $menu = Menu::find($id);
        $menu->sum += 1;
        $menu->save();
        return redirect('/');
    }

    public function cancel(Request $requests){
        $order = Order::find($requests->input('orderID'));
        $user = User::find($order->userID);

        if(md5($user->id.$user->name) !== $requests->input('id'))
            abort(403, 'No Way To Cancel Other Lunch');

        $menu = Menu::find($order->menuID);
        $menu->sum -= 1;
        $menu->save();

        $order->delete();
        return redirect('/');
    }
}
