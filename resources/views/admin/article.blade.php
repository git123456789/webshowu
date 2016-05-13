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
							<th>文章标题</th>
							<th>属性状态</th>
							<th>发布时间</th>
							<th>操作选项</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($articles as $str)
						<tr>
							<td>{{$str->art_id}}</td>
							<td class="textleft">{{$str->art_title}}</td>
							<td style="color: #FF0000;">
								@if ($str->art_status == 1) 草稿 @endif 
								@if ($str->art_status == 2) 待审核 @endif 
								@if ($str->art_status == 3) 已审核 @endif
							</td>
							<td>{{$str->created_at}}</td>
							<td><a href="/admin/article/edit/{{$str->art_id}}">编辑</a></td>
						</tr>
						@endforeach     
					</tbody>
				</table>
			</div>
			<nav>
				{!! $articles->links() !!}
			</nav>
		</div>
	</div>
</div>
@endsection
