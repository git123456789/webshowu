@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">{{$site_title}}</div>
					<ul class="list-group">
						@foreach ($websites as $str)
						<li class="list-group-item">
							<div class="media">
								<a title="{{$str->web_name}}" href="/siteinfo-{{$str->web_id}}.html" class="pull-left" target="_blank">
									<img class="img-thumbnail" alt="{{$str->web_name}}" src="http://api.webthumbnail.org/?width=480&height=330&screen=1280&url={{ $str->web_url }}">
								</a>
								<div class="media-body">
									<h4 class="media-heading">
										<a title="{{$str->web_name}}" href="/siteinfo-{{$str->web_id}}.html" target="_blank">{{$str->web_name}}</a>
									</h4>
									<p>{{$str->web_intro}}</p>
									<address>
										<a title="{{$str->web_name}}" href="/siteinfo-{{$str->web_id}}.html" target="_blank">{{$str->web_url}}</a> - {{$str->updated_at}}
									</address>
								</div>
							</div>
						</li>
						@endforeach
					</ul>
				</div>
				<nav>
					{!! $websites->links() !!}
				</nav>
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
