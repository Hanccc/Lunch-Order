@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if ($errors->has('time'))
            <div class="alert alert-danger" role="alert">{{ $errors->first('time') }}</div>
        @endif
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading">New Feature!</div>
                <div class="panel-body">
                    v 1.0.1<br>
                    * 修复点餐订单按人合并<br>
                    * 修复remember me失效情况<br>
                    * 增加菜单按照热门排序<br>
                    * 增加订单总数排序<br>
                    * 增加定时刷新（5秒刷新一次）<br>
                    * 增加时间限制(10am - 11:40am)<br>
                    * 增加特性栏（feature）<br><br>
                    <div class="col-md-3 col-md-offset-10">2016-3-24</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
