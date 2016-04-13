@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if ($errors->has('time'))
                <div class="alert alert-danger" role="alert">{{ $errors->first('time') }}</div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-12">
                <form class="form-inline" method="POST" action="{{ action('MenuController@addMenu') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="exampleInputName2">Menu Name</label>
                        <input name="name" type="text" class="form-control" placeholder="茄子鸡丁饭">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName2">Menu Price</label>
                        <input name="price" type="number" class="form-control" placeholder="15">
                    </div>
                    <label class="radio-inline">
                        <input type="radio" name="type" value="0"> 饭类
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="type" value="1"> 粉面类
                    </label>
                    <button type="submit" class="btn btn-primary btn-sm">Add Menu</button>
                </form>
            </div>

            <br>
            <br>

            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">Menu</div>
                    <table class="table table-hover">
                        <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->price }} RMB</td>
                                <td><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>
                                    heat: {{ $menu->sum }}</td>
                                <td>
                                    @if($menu->status === 1)
                                        <a class="btn btn-warning btn-xs"
                                           href="{{ action('MenuController@changeStatus', ['id' => $menu->id]) }}">
                                            disable
                                        </a>
                                    @else
                                        <a class="btn btn-success btn-xs"
                                           href="{{ action('MenuController@changeStatus', ['id' => $menu->id]) }}">
                                            enable
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-xs"
                                       href="{{ action('MenuController@delete', ['id' => $menu->id]) }}">
                                        delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
