@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">{{$site_title}}</div>
					<ul class="list-group">
						@foreach ($lablist as $str)
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
					{!! $lablist->links() !!}
				</nav>
			</div>
		</div>
	</div>
</div>
@endsection
