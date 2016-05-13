@extends('layouts.app')

@section('content')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<ul class="list-group">
					<ul class="list-group">
						@foreach ($pages as $str)
							<li class="list-group-item">
								<a target="_blank" title="{{ $str->page_name }}" href="{{ url('/diypage-'.$str->page_id.'.html') }}">{{ $str->page_name }}</a>
							</li>
						@endforeach
					</ul>
				</ul>
			</div>
			<div class="col-md-9">
				<main>
					<article class="post tag-about-ghost tag-release tag-ghost-0-7-ban-ben" id="92">
						<header class="post-head">
							<h1 class="post-title">{{ $page_first->page_name }}</h1>
						</header>
						<section class="post-content">
							{!! $page_first->page_content !!}
						</section>
					</article>
				</main>
			</div>
		</div>
	</div>
</div>
@endsection
