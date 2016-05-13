@extends('layouts.log_reg')

@section('content')
<!-- Login Screen -->
<div class="login-wrapper">
      <a href="{{ url('/') }}"><img width="150" height="45" src="{{ url('images/xinlogo.png') }}" /></a>
      <form method="post" action="">
		<input type="hidden" value="POST" name="_method">
		<input type="hidden" value="{{ csrf_token() }}" name="_token" />
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input class="form-control" type="text" name="email" placeholder="登录账户、找回密码时使用">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" type="password" name="pass" placeholder="6~20个字符">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-check"></i></span><input class="form-control" type="password" name="pass1" placeholder="同上">
          </div>
        </div>
		<div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-group"></i></span><input class="form-control" type="text" name="nick" placeholder="我们对您的称呼">
          </div>
        </div>
		<div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-qq"></i></span><input class="form-control" type="text" name="qq" placeholder="站长相互联系的qq号">
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
        <div class="form-group">
          <label class="checkbox text-left text-padding-left"><input type="checkbox"><span>我同意《注册条款》</span></label>
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="注册">
        <!-- 
		<div class="social-login clearfix">
            <a class="btn btn-primary" href="?mod=connect&oper=init"><i class="fa fa-qq"></i>QQ 账户登录</a>
        </div> 
		-->
        <p>
          已经有账户了？
        </p>
        <a class="btn btn-default-outline btn-block" title="立即登录" href="{{ url('/login') }}">立即登录</a>
      </form>
    </div>
@endsection
