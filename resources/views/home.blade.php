@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
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
                            <td><a class="btn btn-primary btn-xs" href="{{ action('HomeController@order', ['id' => $menu->id]) }}">order</a></td>
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
                                <td>{{ $order->menu->name }}</td>
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
                                <td>{{ $order->name }}</td>
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
