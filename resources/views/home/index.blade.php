@extends('layouts.home')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="panel panel-default">                
				<div class="panel-heading">个人信息</div>
				<ul class="list-group">
					<li class="list-group-item">会员等级：{{ $myself->user_types }}</li>
					<li class="list-group-item">注册时间：{{ $myself->created_at }}</li>
					<li class="list-group-item">您的呢称：{{ $myself->nick_name }}</li>
					<li class="list-group-item">您的邮箱：{{ $myself->user_email }}</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">项目统计</div>
				<!-- List group -->
				<ul class="list-group">
					<li class="list-group-item">
						<span>目前您共提交 <strong>{{ $myself->website }}</strong> 个站点，<span><a href="{{ url('/site/add') }}">继续提交</a>
					</li>
					<li class="list-group-item">
						<span>目前您共发布 <strong>{{ $myself->article }}</strong> 篇文章，<span><a href="{{ url('/art/add') }}">继续发布</a>
					</li>
				</ul>
			</div>
		</div>
	</div>  
</div>
@endsection
