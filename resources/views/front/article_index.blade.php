@extends('layouts.app')

@section('content')
<div class="xiuzhan">
	<div class="container">
		<div class="row">
			@foreach ($cates as $str)
			<div class="col-sm-3">
				<h2>
					<strong>
						<a class="pink" target="_blank" title="{{ $str->cate_name }}" href="{{ url('/article/'.$str->cate_dir ) }}">
							<span style="font-size:24px;">{{ $str->cate_name }}</span>
						</a>
					</strong>
				</h2>
				<ul>
					@foreach ($str['site_array'] as $str_array)
					<li>
						<a target="_blank" href="{{ url('/artinfo-'.$str_array->art_id.'.html') }}" title="{{ $str_array->title }}">
							{{ str_limit($str_array->title,30,'') }}
						</a>
					</li>
					@endforeach
				</ul>
			</div>
			@endforeach
		</div>
	</div>
</div>
@endsection