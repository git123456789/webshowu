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
							<th>网站名称</th>
							<th>网站地址</th>
							<th>属性状态</th>
							<th>提交时间</th>
							<th>操作选项</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($websites as $str)
							<tr>
								<td>{{ $str->web_id}}</td>
								<td class="textleft">{{ $str->web_name }}</td>
								<td class="textleft">{{ $str->web_url }}</td>
								<td style="color: #FF0000;">
									@if ($str->web_status == 1) 黑名单 @endif 
									@if ($str->web_status == 2) 待审核 @endif 
									@if ($str->web_status == 3) 已审核 @endif
								</td>
								<td>{{ $str->created_at}}</td>
								<td><a href="/admin/website/edit/{{ $str->web_id }}">修改</a></td>
							</tr>
						@endforeach        
					</tbody>
				</table>
			</div>
			<nav>
				{!! $websites->links() !!}
			</nav>
		</div>
	</div>
</div>
@endsection
