<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/4/12
 * Time: 上午11:09
 */

namespace App\Services;


use App\Menu;
use Illuminate\Http\Request;

class MenuService
{

    public static function getMenuOrderByHeat()
    {
        $sql = "SELECT * FROM menu INNER JOIN `order` `order`.menuID = menu.id GROUP BY group_id ORDER BY count()";
    }

    public static function changeStatus(Request $request){

        $menu = Menu::find($request->input('id'));

        if(!$menu)
            return back()->withErrors('找不到该菜单');

        $menu->status = $menu->status == 1?0:1;
        $menu->save();
    }
}