@extends('layouts.log_reg')

@section('content')
<!-- Login Screen -->
<div class="login-wrapper">
  <a href="{{ url('/') }}"><img width="150" height="45" src="{{ url('images/xinlogo.png') }}" /></a>
  <form action="" method="post">
	<input type="hidden" value="POST" name="_method">
	<input type="hidden" value="{{ csrf_token() }}" name="_token" />
	<div class="form-group">
	  <div class="input-group">
		<span class="input-group-addon"><i class="fa fa-envelope"></i></span><input class="form-control" placeholder="用户名/邮箱" name="username" type="text">
	  </div>
	</div>
	<div class="form-group">
	  <div class="input-group">
		<span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" placeholder="密码" name="password" type="password">
	  </div>
	</div>
	@if(count($errors) > 0)
	<div class="alert alert-danger" role="alert">
		<i class="fa fa-exclamation-circle"></i> 
		<strong>
			@foreach($errors->all() as $error)
				{{ $error }} &nbsp;&nbsp;
			@endforeach
		</strong>
	</div>
	@endif
	<!-- <a class="pull-right" href="?mod=getpwd">忘记密码了？</a> -->
	<div class="text-left text-padding-left">
	  <label class="checkbox"><input type="checkbox"><span>自动登录</span></label>
	</div>
	<input class="btn btn-lg btn-primary btn-block" type="submit" value="登录">
	<!-- 
	<div class="social-login clearfix">
		<a class="btn btn-primary" href="?mod=connect&oper=init"><i class="fa fa-qq"></i>QQ 账户登录</a>
	</div> 
	-->
  </form>
  <p>
	 还没有账户？
  </p>
  <a class="btn btn-default-outline btn-block" title="立即注册" href="{{ url('/register') }}">立即注册</a>
</div>
@endsection
