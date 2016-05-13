@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<main>
					<article class="post tag-about-ghost tag-release tag-ghost-0-7-ban-ben" id="92">
						<header class="post-head">
							<h4 class="post-title">{{$websites->web_name}}</h4>
						</header>
						<section class="post-content">
							<a class="" target="_blank" href="{{$websites->web_url}}">
								<img src="http://api.webthumbnail.org/?width=480&height=330&screen=1280&url={{ $websites->web_url }}" alt="{{$websites->web_name}}" class="img-rounded">
							</a>

							<span>人气：<em>{{$websites->web_views}}</em></span>
							<span>PR：<em>{{$websites->web_grank}}</em></span>
							<span>权重：<em>{{$websites->web_brank}}</em></span>
							<span>SR：<em>{{$websites->web_srank}}</em></span>
							<span>Alexas：<em>{{$websites->web_arank}}</em></span>
							<span>入站：<em>{{$websites->web_instat}}</em></span>
							<span>出站：<em>{{$websites->web_outstat}}</em></span>
							<span>收录：<em>{{$websites->created_at}}</em></span>
							<span>更新：<em>{{$websites->updated_at}}</em></span>

							<div class="media">
								<div class="media-body">
									<ul class="list-group">
										<li>
											<strong>网站地址：</strong>
											<a href="{{$websites->web_url}}" target="_blank" class="visit">
												<font color="#008000">{{$websites->web_url}}</font>
											</a>
										</li>
										<li><strong>服务器IP：</strong>{{$websites->web_ip}}</li>
										<li><strong>网站描述：</strong><span style="line-height: 23px;">{{$websites->web_intro}}</span></li>
										<li><strong>站长QQ：</strong>{{$websites->user_qq}}</li>
										<li><strong>TAG标签：</strong>
										@foreach ($webtags as $str)
										<a href="{{url('/tags/'.$str)}}" title="{{$str}}" target="_blank">{{$str}}</a>　
										@endforeach
										</li>
										<li>
											<strong>相关查询：</strong>
											<a href="http://seo.chinaz.com/?q={{$websites->web_url}}" target="_blank">网站综合信息查询</a>　|　
											<a href="http://tool.chinaz.com/baidu/?wd={{$websites->web_url}}&lm=0&pn=0" target="_blank">百度近日收录查询</a>　|　
											<a href="http://linkche.aizhan.com/{{$websites->web_url}}/" target="_blank">友情链接查询</a>　|　
											<a href="http://pr.chinaz.com/?PRAddress={{$websites->web_url}}" target="_blank">PR查询</a>
										</li>
									</ul>
								</div>
							</div>
						</section>
						<section class="post-content">
							<!-- 多说评论框 start --> <div class="ds-thread" data-thread-key="{{$websites->web_id}}" data-title="{{$websites->web_name}}" data-url="/siteinfo-{{$websites->web_id}}.html"></div> <!-- 多说评论框 end --> <!-- 多说公共JS代码 start (一个网页只需插入一次) --> <script type="text/javascript"> var duoshuoQuery = {short_name:"wwwwebshowu"}; (function() { var ds = document.createElement('script'); ds.type = 'text/javascript';ds.async = true; ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js'; ds.charset = 'UTF-8'; (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds); })(); </script> <!-- 多说公共JS代码 end --> 
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
