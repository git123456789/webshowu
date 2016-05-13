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
	<link rel="icon" type="image/png" href="favicon.png">
    <!-- Fonts -->
    <link href="{{ url('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>
    <!-- Styles -->

    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ url('css/auto.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ url('css/buttons.css') }}" rel='stylesheet' type='text/css'>
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body id="app-layout">
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
				 <span class="sr-only"></span>
				 <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="{{ url('/') }}" title="秀站分类目录免费收录各类优秀网站，快速提升网站流量">
				<img width="213" height="50" alt="秀站分类目录免费收录各类优秀网站，快速提升网站流量" src="{{ url('images/xinlogo.png') }}">
			  </a>
			</div>
			<div class="collapse navbar-collapse" id="example-navbar-collapse">
			  <ul class="nav navbar-nav">
				<li><a target="_blank" title="秀目录" href="{{ url('/webdir') }}">秀目录</a></li>
				<li><a target="_blank" title="秀资讯" href="{{ url('/article') }}">秀资讯</a></li>
				<li><a target="_blank" title="秀文档" href="http://doc.webshowu.com">秀文档</a></li>
			  </ul>
			  <form class="navbar-form navbar-left" role="search" target="_blank" action="http://zhannei.baidu.com/cse/search" method="get">
		        <div class="form-group">
		          <input type="text" name="q" class="form-control" placeholder="请输入你要搜索的内容">
		          <input type="hidden" name="s" value="5924146839921945097"/>
		        </div>
		        <button type="submit" class="btn btn-default-outline">搜索</button>
		      </form>
			  @if (empty(Session::get('username')))
			  <a class="btn btn-default-outline navbar-text navbar-right navbar-link" title="秀站登录" href="{{ url('/login') }}" target="_blank">登录</a>
			  @else
			  <a class="btn btn-default-outline navbar-text navbar-right navbar-link" title="安全退出" href="{{ url('/logout') }}" target="_blank">安全退出</a>
			  <a class="btn btn-default-outline navbar-text navbar-right navbar-link" title="个人中心" href="{{ url('/home') }}" target="_blank">个人中心</a>
			  @endif
			</div>
		</div>
	</nav>
	<div class="site-header"></div>
    @yield('content')
    <!-- JavaScripts -->
	<div class="foot">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<p>
					@foreach ($pages as $str)
					<a target="_blank" title="{{ $str->page_name }}" href="{{ url('/diypage-'.$str->page_id.'.html') }}">{{ $str->page_name }}</a> | 
					@endforeach
					<!-- <a target="_blank" title="站点地图" href="{{ url('/sitemap') }}">站点地图</a> -->
				</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
				<p>
					<a href="http://www.webshowu.com/">秀站分类目录</a>&nbsp;&nbsp;北京儒尚科技有限公司【<a rel="nofollow" href="http://www.miibeian.gov.cn">京ICP备14053701号-4</a>】&nbsp;&nbsp;QQ群：57176386&nbsp;&nbsp;快审qq：897284312&nbsp;&nbsp;<script src="http://s95.cnzz.com/z_stat.php?id=1257630163&web_id=1257630163" language="JavaScript"></script>
				</p>
				</div>
			</div>
		</div>
	</div>	
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
	<script>
	(function(){
		var bp = document.createElement('script');
		bp.src = '//push.zhanzhang.baidu.com/push.js';
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(bp, s);
	})();
	</script>
	<script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?73d6f22b97da2e1d37ee429edbecaf61";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
	<script>
		$(function() {
		  $(window).scroll(function() {
			if($(this).scrollTop() != 0) {
			  $("#toTop").fadeIn(); 
			} else {
			  $("#toTop").fadeOut();
			}
		  });
		  $("body").append("<div id=\"toTop\" style=\"border:1px solid #444;background:#333;color:#fff;text-align:center;padding:10px 13px 7px 13px;position:fixed;bottom:10px;right:10px;cursor:pointer;display:none;font-family:verdana;font-size:22px;\">^</div>");
		  $("#toTop").click(function() {
			$("body,html").animate({scrollTop:0},800);
		  });
		});
	</script>
	<script>
	$(function(){
		var $timeline_block = $('.cd-timeline-block');
		//hide timeline blocks which are outside the viewport
		$timeline_block.each(function(){
			if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
				$(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
			}
		});
		//on scolling, show/animate timeline blocks when enter the viewport
		$(window).on('scroll', function(){
			$timeline_block.each(function(){
				if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.cd-timeline-img').hasClass('is-hidden') ) {
					$(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
				}
			});
		});
	});
	</script>
	<script>
	(function(){
	   var src = (document.location.protocol == "http:") ? "http://js.passport.qihucdn.com/11.0.1.js?3bb1e90adc41890b9e10aaf78d8e5811":"https://jspassport.ssl.qhimg.com/11.0.1.js?3bb1e90adc41890b9e10aaf78d8e5811";
	   document.write('<script src="' + src + '" id="sozz"><\/script>');
	})();
	</script>
</body>
</html>
