<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;

class MenuController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        $menu = Menu::orderBy('sum', 'desc')->orderBy('price', 'desc')->get();
        return view('menu', ['menus' => $menu]);
    }


    public function addMenu(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|bail',
            'type' => 'required|bail',
            'price' => 'required',
        ]);

        Menu::create(['name' => $request->input('name'), 'price' => $request->input('price'), 'type' => $request->input('type')]);

        return redirect('/');
    }

    public function changeStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        MenuService::changeStatus($request);

        return redirect('/menu');
    }
}
