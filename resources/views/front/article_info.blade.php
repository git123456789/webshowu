@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<main>
					<article class="post tag-about-ghost tag-release tag-ghost-0-7-ban-ben" id="92">
						<header class="post-head">
							<h1 class="post-title">{{$articles->art_title}}</h1>
							<section class="post-meta">
								@if ($articles->copy_url == '')
									<span class="author">来源：<a title="{{$articles->copy_from}}" href="" target="_blank">{{$articles->copy_from}}</a></span>
								@else
									<span class="author">来源：<a title="{{$articles->copy_from}}" href="{{$articles->copy_url}}" target="_blank">{{$articles->copy_from}}</a></span>
								@endif
								•
								<time title="{{$articles->updated_at}}" datetime="{{$articles->updated_at}}" class="post-date">{{$articles->updated_at}}</time>
							</section>
							<section class="post-meta">
								@foreach ($arttags as $str)
									<a target="_blank" title="{{$str}}" href="http://zhannei.baidu.com/cse/search?q={{$str}}&s=5924146839921945097" class="button button-pill button-tiny">{{$str}}</a>
								@endforeach
							</section>
						</header>
						<section class="post-content">
							{!! $articles->art_content !!}
						</section>
						<section class="post-content">
							<div class="pull-left">上一个：@if ($prev)<a href="/artinfo-{{$prev->art_id}}.html">{{$prev->art_title}}</a> @else 没有了 @endif</div>
							<div class="pull-right">下一个：@if ($next)<a href="/artinfo-{{$next->art_id}}.html">{{$next->art_title}}</a> @else 没有了 @endif</div>
						</section>
						<section class="post-content">
							<!-- 多说评论框 start --> <div class="ds-thread" data-thread-key="{{$articles->art_id}}" data-title="{{$articles->art_title}}" data-url='{{ url("/artinfo-$articles->art_id") }}.html'></div> <!-- 多说评论框 end --> <!-- 多说公共JS代码 start (一个网页只需插入一次) --> <script type="text/javascript"> var duoshuoQuery = {short_name:"wwwwebshowu"}; (function() { var ds = document.createElement('script'); ds.type = 'text/javascript';ds.async = true; ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js'; ds.charset = 'UTF-8'; (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds); })(); </script> <!-- 多说公共JS代码 end --> 
						</section>
					</article>
					@if ($mobile != '1')
						<script>document.write(unescape('%3Cdiv id="hm_t_94820"%3E%3C/div%3E%3Cscript charset="utf-8" src="http://crs.baidu.com/t.js?siteId=73d6f22b97da2e1d37ee429edbecaf61&planId=94820&async=0&referer=') + encodeURIComponent(document.referrer) + '&title=' + encodeURIComponent(document.title) + '&rnd=' + (+new Date) + unescape('"%3E%3C/script%3E'));</script>
					@else
						<script>document.write(unescape('%3Cdiv id="hm_t_94821"%3E%3C/div%3E%3Cscript charset="utf-8" src="http://crs.baidu.com/t.js?siteId=73d6f22b97da2e1d37ee429edbecaf61&planId=94821&async=0&referer=') + encodeURIComponent(document.referrer) + '&title=' + encodeURIComponent(document.title) + '&rnd=' + (+new Date) + unescape('"%3E%3C/script%3E'));</script>
					@endif

				</main>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">推荐资讯</div>
					<ul class="list-group">
						@foreach ($art_list as $str)
						<li class="list-group-item">
							<a title="{{ $str->art_title }}" href="/artinfo-{{$str->art_id}}.html" target="_blank">{{ $str->art_title }}</a>
						</li>
						@endforeach
					</ul>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">推荐站点</div>
					<ul class="list-group">
						@foreach ($site_list as $str)
						<li class="list-group-item">
							<a title="{{ $str->web_name }}" href="/siteinfo-{{$str->web_id}}.html" target="_blank">
								<img src="http://api.webthumbnail.org/?width=480&height=330&screen=1280&url={{ $str->web_url }}" width="100" height="80" alt="{{ $str->web_name }}" />
							</a>
							<strong>
								<a title="{{ $str->web_name }}" href="/siteinfo-{{$str->web_id}}.html" title="{{ $str->web_name }}" target="_blank">{{ $str->web_name }}</a>
							</strong>
							<p>{{ $str->web_intro }}</p>
							<address>
								<a title="{{ $str->web_name }}" href="/siteinfo-{{$str->web_id}}.html" target="_blank" class="visit" target="_blank">{{ $str->web_furl }}</a>
							</address>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
