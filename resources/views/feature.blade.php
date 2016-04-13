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
                    v 2.0<br>
                    * 菜单界面管理<br>
                    * 增加禁用菜单<br>
                    <div class="col-md-3 col-md-offset-10">2016-4-13</div>
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">New Feature!</div>
                <div class="panel-body">
                    v 1.0.2<br>
                    * 修复记住密码失效<br>
                    * 默认记住密码<br>
                    * 修复total粉面显示错误<br>
                    * 面与饭分开显示<br>
                    * 修复时间限制（10am - 11:45am)<br>
                    <div class="col-md-3 col-md-offset-10">2016-3-28</div>
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">1.0.1 Feature</div>
                <div class="panel-body">
                    v 1.0.1<br>
                    * 修复点餐订单按人合并<br>
                    {{--* 修复remember me失效情况<br>--}}
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
