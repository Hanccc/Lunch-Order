@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if ($errors->has('time'))
            <div class="alert alert-danger" role="alert">{{ $errors->first('time') }}</div>
        @endif
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Menu</div>
                    <table class="table table-hover">
                        <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->price }} RMB</td>
                            <td><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> heat: {{ $menu->sum }}</td>
                            <td>
                                @if($menu->type == 0)
                                <a class="btn btn-primary btn-xs" href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 0]) }}">order</a>
                                @else
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            面条类 <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 1]) }}">面</a></li>
                                            <li><a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 2]) }}">河粉</a></li>
                                            <li><a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 3]) }}">米粉</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-success">
                    <div class="panel-heading">Order</div>
                    <table class="table">
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    {{ $order->menu->name }}
                                    @if($order->type == 1)
                                        面
                                    @elseif($order->type == 2)
                                        河粉
                                    @elseif($order->type == 3)
                                        米粉
                                    @endif
                                </td>
                                <td>{{ $order->menu->price }} RMB</td>
                                <td>
                                @if($order->user->id == $userID)
                                    <a class="btn btn-danger btn-xs" href="{{ action('HomeController@cancel', ['id' => md5($userID.$order->user->name), 'orderID' => $order->id]) }}">cancel</a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-danger">
                    <div class="panel-heading">Total</div>
                    <table class="table">
                        <tbody>
                        @foreach($sum as $order)
                            <tr>
                                <td>{{ $order->name }}
                                    @if($order->type == 1)
                                        面
                                    @elseif($order->type == 2)
                                        河粉
                                    @elseif($order->type == 3)
                                        米粉
                                    @endif
                                </td>
                                <td>{{ $order->total }} piece</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
