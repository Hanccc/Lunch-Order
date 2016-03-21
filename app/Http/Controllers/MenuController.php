<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;

class MenuController extends Controller
{

    public function __construct()
    {
        if (Gate::denies('isAdmin')) {
            abort(403);
        }

    }

    public function index()
    {
        echo 's';
    }
}
