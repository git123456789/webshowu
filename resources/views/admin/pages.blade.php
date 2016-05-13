@extends('layouts.home')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>页面名称</th>
							<th>页面说明</th>
							<th>操作选项</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($pages as $str)
						<tr>
							<td>{{$str->page_id}}</td>
							<td class="textleft">{{$str->page_name}}</td>
							<td>{{$str->page_intro}}</td>
							<td><a href="/admin/pagelist/edit/{{$str->art_id}}">修改</a></td>
						</tr>
						@endforeach     
					</tbody>
				</table>
			</div>
			<nav>
				{!! $pages->links() !!}
			</nav>
		</div>
	</div>
</div>
@endsection
