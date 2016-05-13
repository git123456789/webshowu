@extends('layouts.app')

@section('content')
<div class="xiuzhan">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				@if($success !='xiumei')	
				<div class="alert alert-success text-center">{{ $success }}</div>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<h2>
					<strong>
						<a class="pink" target="_blank" title="活跃网站-基于反向链接实时更新，任何网站只要有一个来访IP，就会自动排到第一名！" href="{{ url('/webdir') }}">
							<span style="font-size:24px;">活跃网站</span>
						</a>
					</strong>
				</h2>
				<ul>
					@foreach ($hotsites as $str)
					<li>
						<a target="_blank" href="{{ url('/siteinfo-'.$str->web_id.'.html') }}" title="{{ $str->web_name }}">
							{{ str_limit($str->web_name,30,'') }}
						</a>
					</li>
					@endforeach
				</ul>
			</div>
			<div class="col-sm-3">
				<h2>
					<strong>
						<a class="purple" target="_blank" title="秀资讯" href="{{ url('/article') }}">
							<span style="font-size:24px;">秀资讯</span>
						</a>
					</strong>
				</h2>
				<ul>
					@foreach ($articles as $str)
						<li>
							<a target="_blank" href="{{ url('/artinfo-'.$str->art_id.'.html') }}" title="{{ $str->art_title }}">
								{{ str_limit($str->art_title,30,'') }}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
			<div class="col-sm-3">
				<h2>
					<strong>
						<a class="pink" target="_blank" title="秀目录" href="{{ url('/webdir') }}">
							<span style="font-size:24px;">秀目录</span>
						</a>
					</strong>
				</h2>
				<ul>
					@foreach ($websites as $str)
					<li>
						<a target="_blank" href="{{ url('/siteinfo-'.$str->web_id.'.html') }}" title="{{ $str->web_name }}">
							{{ str_limit($str->web_name,30,'') }}
						</a>
					</li>
					@endforeach
				</ul>
			</div>
			<div class="col-sm-3">
				<h2>
					<strong>
						<a class="pink" target="_blank" title="秀标签" href="{{ url('/webdir') }}">
							<span style="font-size:24px;">秀标签</span>
						</a>
					</strong>
				</h2>
				@foreach ($lables as $str)
					<a target="_blank" href="{{url('/tags/'.$str->lab_name)}}" title="{{ $str->lab_name }}">{{ $str->lab_name }}</a>
				@endforeach
			</div>
		</div>
		<div class="row">
			<h2>
				<strong>
					<a class="green" target="_blank" title="秀导航"><span style="font-size:24px;">秀导航</span></a>
				</strong>
			</h2>
			@foreach ($cates as $str)
				<div class="row">
					<div class="col-sm-12">
						<h4 class="hz-line-left">
							{{ $str['cate_name'] }}
							<a target="_blank" title="{{ $str['cate_name'] }}更多" href="{{ url('/webdir/'.$str['cate_id']) }}" class="btn btnstyle">更多</a>
						</h4>
					</div>
				</div>
				<div class="row">
					@foreach ($str['site_array'] as $str_array)
						<div class="col-sm-3 col-md-2">
							@if ($mobile != '1')
								<div class="card">
									<div class="card-image">
										<a title="{{ $str_array->web_name }}" target="_blank" href="{{ url('/siteinfo-'.$str_array->web_id.'.html') }}">
											<img alt="{{ $str_array->web_name }}" src="http://api.webthumbnail.org/?width=480&height=330&screen=1280&url={{ $str_array->web_url }}">
											<span class="card-title">{{ str_limit($str_array->web_name,15,'') }}</span>
										</a>
									</div>
									<div class="card-content">
										<p style="line-height: 24px;" class="small clearfix">
											<a target="_blank" title="{{ $str_array->nick_name}}" href="{{ url('/footmark-'.$str_array->user_id.'.html') }}" class="block-author-simple pull-right">
												<img alt="{{ $str_array->nick_name}}" src="{{ url('/images/savatar.jpg') }}" class="img-circle">
												<span style="vertical-align: initial;">{{ $str_array->nick_name}}</span>
											</a>
											<span class="on-right"><i class="fa fa-eye"></i>{{ $str_array->web_views}}</span>
										</p>
									</div>
								</div>
							@else
								<div class="card">
									<div class="card-content">
										<a title="{{ $str_array->web_name }}" target="_blank" href="{{ url('/siteinfo-'.$str_array->web_id.'.html') }}">
											<span class="card-title">{{ $str_array->web_name }}</span>
										</a>
										<p style="line-height: 24px;" class="small clearfix">
											<a target="_blank" title="{{ $str_array->nick_name}}" href="{{ url('/footmark-'.$str_array->user_id.'.html') }}" class="block-author-simple pull-right">
												<img alt="{{ $str_array->nick_name}}" src="{{ url('/images/savatar.jpg') }}" class="img-circle">
												<span style="vertical-align: initial;">{{ $str_array->nick_name}}</span>
											</a>
											<span class="on-right"><i class="icon-eye-open"></i>{{ $str_array->web_views}}</span>
										</p>
									</div>
								</div>
							@endif
						</div>
					@endforeach
				</div>
			@endforeach
		</div>
		<div class="row">
			<h2>
				<strong>
					<a class="blue" target="_blank" title="友情链接"><span style="font-size:24px;">友情链接</span></a>
				</strong>
			</h2>
			<ul class="row">
				@foreach ($links as $str)
					<li class="col-sm-6 col-md-2">
						<a href="{{ $str->link_url }}" target="_blank" title="{{ $str->link_name }}">{{ $str->link_name }}</a>
					</li>
				@endforeach
			</ul>
		</div>
		<div class="row">
			<h2>
				<strong>
					<a class="blue" target="_blank" title="程序源码"><span style="font-size:24px;">程序源码</span></a>
				</strong>
			</h2>
			<ul>
				<li class="col-sm-6 col-md-3">
					<a href="http://git.oschina.net/webshowu/webshowu" target="_blank" title="开源中国下载">开源中国下载</a>
				</li>
			</ul>
			
		</div>
	</div>
</div>
@endsection
