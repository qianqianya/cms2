{{-- 用户登录--}}

@extends('layouts.app')

@section('content')
    <form class="form-signin" action="/login" method="post" style="margin-left:500px;">
        {{csrf_field()}}
        <h2 class="form-signin-heading">请登录</h2>
        <label for="inputEmail">Email</label>
        <input type="email" name="u_email" id="inputEmail" class="form-control" placeholder="@" required autofocus style="width:500px;">
        <label for="inputPassword" >Password</label>
        <input type="password" name="u_pwd" id="inputPassword" class="form-control" placeholder="***" required style="width:500px;">
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me">记住密码
            </label>
        </div>
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit" style="width:100px;;float: left">登录</button>
        <a href="{{url('')}}" class="btn btn-lg btn-primary btn-block" style="width:100px;float: left;margin-top:0px;margin-left:300px;">注册</a>
    </form>
@endsection
