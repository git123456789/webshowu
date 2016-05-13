<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="{{ $site_keywords }}" />
	<meta name="Description" content="{{ $site_description }}" />
	<title>{{ $site_title }}</title>
    <!-- Fonts -->
    <link href="{{ url('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ url('css/custom.css') }}" rel='stylesheet' type='text/css'>
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
	@include('UEditor::head');
</head>
<body id="app-layout">
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">秀站用户中心</a>
			</div>

			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-left">
					<li><a href="{{ url('/') }}">站点首页</a></li>
					<li><a href="{{ url('/footmark-'.$myself->user_id) }}.html">我的足迹</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							管理中心<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('/home') }}">我的首页</a></li>
							<li><a href="{{ url('/site/add') }}">提交网站</a></li>
							<li><a href="{{ url('/site') }}">网站管理</a></li>
							<li><a href="{{ url('/art/add') }}">在线投稿</a></li>
							<li><a href="{{ url('/art') }}">我的投稿</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">系统管理<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('/profile') }}">个人资料</a></li>
							<li><a href="{{ url('/editpwd') }}">修改密码</a></li>
						</ul>
					</li>
					
					@if ($myself->user_type == 'admin')
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">后台管理<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('/admin/pagelist') }}">自定义页面</a></li>
							<li><a href="{{ url('/admin/article') }}">文章列表</a></li>
							<li><a href="{{ url('/admin/website') }}">站点列表</a></li>
						</ul>
					</li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">欢迎你：{{ $myself->nick_name }}</a></li>
					<li><a href="{{ url('/logout') }}">退出</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<h3 class="sub-header">{{ $pagename }}</h3>
			</div>
		</div>
	</div>
    @yield('content')
    <!-- JavaScripts -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
	<script>
	//获取META
	function getmeta() {
		var url = $("#web_url").val();
		if (url == '') {
			alert('请输入网站域名！');
			$("#web_url").focus();
			return false;
		}
		$("#meta_btn").val('正在获取，请稍候...'); 
		$.ajax({
				type:"POST",
				url: "/home/ajaxget/crawl",
				data: "url="+url,
				cache:false,
				datatype: "json",
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
				},
				success: function(data){
					if(data.code == 200){
						$("#web_name").val(data.web_name);
						$("#web_tags").val(data.web_tags);
						$("#web_intro").val(data.web_intro);
						$("#web_ip").val(data.web_ip);
						$("#web_grank").val(data.web_grank);
						$("#web_brank").val(data.web_brank);
						$("#web_srank").val(data.web_srank);
						$("#web_arank").val(data.web_arank);
					}else{
						alert(data.meg);
					}
					$("#meta_btn").val('重新获取');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					var meg = jQuery.parseJSON( XMLHttpRequest.responseText ); 
					alert(meg.url);
					$("#meta_btn").val('重新获取');
				}
		});

	}
	</script>
</body>
</html>
