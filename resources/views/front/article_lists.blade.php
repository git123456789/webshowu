@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">{{$site_title}}</div>
					<ul class="list-group">
						@foreach ($articles as $str)
							<li class="list-group-item">
								<div class="media">
									<div class="media-body">
										<h4 class="media-heading">
											<a title="{{$str->art_title}}" href='{{ url("/artinfo-$str->art_id") }}.html' target="_blank">{{$str->art_title}}</a>
										</h4>
										<p>{{$str->art_intro}}</p>
										<p>{{$str->updated_at}}</p>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
				<nav>
					{!! $articles->links() !!}
				</nav>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">推荐资讯</div>
					<ul class="list-group">
						@foreach ($art_list as $str)
						<li class="list-group-item">
							<a title="{{ $str->art_title }}" href='{{ url("/artinfo-$str->art_id") }}.html' target="_blank">{{ $str->art_title }}</a>
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
