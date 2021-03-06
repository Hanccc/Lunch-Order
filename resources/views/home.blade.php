@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if ($errors->has('time'))
                <div class="alert alert-danger" role="alert">{{ $errors->first('time') }}</div>
            @endif
            <div class="col-md-6 col-md-offset-0" style="margin: 0 0 10px 15px">
                <button id="showAll" class="random btn btn-success">看全部</button>
                <button id="riceOnly" class="random btn btn-warning">只看饭</button>
                <button id="noodleOnly" class="random btn btn-info">看粉面</button>
                <button class="random btn btn-danger" data-toggle="modal" data-target="#randomModel">随机点</button>
            </div>
            <div class="col-md-12">
                <div class="col-md-5">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Menu</div>
                        <table class="table table-hover">
                            <tbody>
                            <?php $i = 0;?>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->name }}</td>
                                    <td>{{ $menu->price }} RMB</td>
                                    <td>
                                        @if($i < 5)
                                            <span class="glyphicon glyphicon-fire" aria-hidden="true"></span>
                                        @endif
                                        <?php $i++;?>
                                        heat: {{ $menu->sum }}</td>
                                    <td>
                                        @if($menu->type == 0)
                                            <a class="btn btn-primary btn-xs"
                                               href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 0, 'pack' => 0]) }}">order</a>
                                            <a class="btn btn-info btn-xs"
                                               href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 0, 'pack' => 1]) }}">takeout</a>
                                        @else
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    面条类 <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 1, 'pack' => 0]) }}">面</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 2, 'pack' => 0]) }}">河粉</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ action('HomeController@order', ['id' => $menu->id, 'type' => 3, 'pack' => 0]) }}">米粉</a>
                                                    </li>
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
                                    <td>
                                        @if($order->user->id == $userID)
                                            <a class="btn btn-danger btn-xs"
                                               href="{{ action('HomeController@cancel', ['id' => md5($userID.$order->user->name), 'orderID' => $order->id]) }}">cancel</a>
                                        @endif
                                    </td>

                                    <td>
                                    @if($order->pack == 1)
                                        <span class="label label-info">takeout</span>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">takeout Total</div>
                        <table class="table">
                            <tbody>
                            @foreach($sum as $order)
                                @if($order['type'] == 0 && $order['pack'] == 1)
                                    <tr>
                                        <td>{{ $order['menu'] }}</td>
                                        <td>{{ $order['sum'] }} piece</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-danger">
                        <div class="panel-heading">Rice Total</div>
                        <table class="table">
                            <tbody>
                            @foreach($sum as $order)
                                @if($order['type'] == 0 && $order['pack'] == 0)
                                    <tr>
                                        <td>{{ $order['menu'] }}</td>
                                        <td>{{ $order['sum'] }} piece</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-warning">
                        <div class="panel-heading">Noodle Total</div>
                        <table class="table">
                            <tbody>
                            @foreach($sum as $order)
                                @if($order['type'] == 1)
                                    <tr>
                                        <td>{{ $order['menu'] }}</td>
                                        <td>{{ $order['sum'] }} piece</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="randomModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">随机点</h4>
                </div>
                <div class="modal-body">
                    <p>随机点到了: <span id="randomMenu"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">算了,不玩了</button>
                    <button type="button" class="btn btn-primary random">我不服,重来</button>
                    <a type="button" id="choose" class="btn btn-success" href="">好叻,就这个味~</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script language="JavaScript">

        $(document).ready(function () {

            var menus = [];
                    @foreach($menus as $menu)
                        @if($menu->type == 0)
                            var menu = Array()
                            menu.push("{{ $menu->name }}")
                            menu.push("{{ $menu->id }}")
                            menus.push(menu)
                        @endif
                    @endforeach

            console.log(menus)

            function randomMenu() {
                var menu = menus[Math.floor(Math.random() * menus.length + 1) - 1]
                $("#randomMenu").text(menu[0])
                $("#choose").attr("href", "/order/" + menu[1] + "/0")
            }

            $(".random").click(function () {
                randomMenu()
            })

            $("#riceOnly").click(function(){
                $("div.col-md-5 a.btn-xs").parents("tr").show();
                $("div.col-md-5 ul.dropdown-menu").parents("tr").hide();
            })

            $("#noodleOnly").click(function(){
                $("div.col-md-5 ul.dropdown-menu").parents("tr").show();
                $("div.col-md-5 a.btn-xs").parents("tr").hide();
            })

            $("#showAll").click(function(){
                $("div.col-md-5 ul.dropdown-menu").parents("tr").show();
                $("div.col-md-5 a.btn-xs").parents("tr").show();
            })
        })
    </script>
@endsection
