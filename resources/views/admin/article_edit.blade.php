@extends('layouts.home')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<form class="form-horizontal" name="myfrom" id="myfrom" method="post" action="">
				<input type="hidden" value="POST" name="_method">
				<input type="hidden" value="{{ csrf_token() }}" name="_token" />
				<input type="hidden" value="{{ $edit_id }}" name="edit_id" />
				@if(count($errors) > 0)
				<div class="alert alert-danger" role="alert">
					<i class="fa fa-exclamation-circle"></i> 
					<strong>
						@foreach($errors->all() as $error)
							{{ $error }} &nbsp;&nbsp;
						@endforeach
					</strong>
				</div>
				@endif
				<div class="form-group">
					<label class="col-sm-2 control-label" for="cate_id">选择分类：</label>
					<div class="col-sm-10">
						<select id="cate_id" name="cate_id" class="form-control">
							{!! $category_option !!}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_title">文章标题：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->art_title }}" placeholder="请输入文章标题" id="art_title" class="form-control" name="art_title">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_tags">TAG标签：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->art_tags }}" placeholder="请输入TAG标签" id="art_tags" class="form-control" name="art_tags">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="copy_from">内容来源：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->copy_from }}" placeholder="请输入内容来源" id="copy_from" class="form-control" name="copy_from">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="copy_url">来源地址：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->copy_url }}" placeholder="请输入来源地址" id="copy_url" class="form-control" name="copy_url">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_intro">内容摘要：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->art_intro }}" placeholder="请输入内容摘要" id="art_intro" class="form-control" name="art_intro">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_content">文章内容：</label>
					<div class="col-sm-10">
						<!-- 加载编辑器的容器 -->
						<script id="art_content" name="art_content" type="text/plain">{!! $row->art_content !!}</script>
						<!-- 实例化编辑器 -->
						<script type="text/javascript">
							var ue = UE.getEditor('art_content');
								ue.ready(function() {
								ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.    
							});
						</script>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_views">浏览次数：</label>
					<div class="col-sm-10">
						<input type="text" value="{{ $row->art_views }}" placeholder="请输入内容摘要" id="art_views" class="form-control" name="art_views">次
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="inputEmail3">属性设置：</label>
					<div class="col-sm-10">
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="art_ispay" @if( $row->art_ispay =='1' ) checked="" @endif >付费&nbsp;&nbsp;
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="art_istop" @if( $row->art_istop =='1' ) checked="" @endif >置顶&nbsp;&nbsp;
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="art_isbest" @if( $row->art_isbest =='1' ) checked="" @endif >推荐&nbsp;&nbsp;
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="art_status">文章状态：</label>
					<div class="col-sm-10">
						<select name="art_status" class="form-control">
							<option value="1" @if( $row->art_status =='1' ) selected="" @endif >草稿</option>
							<option value="2" @if( $row->art_status =='2' ) selected="" @endif >待审核</option>
							<option value="3" @if( $row->art_status =='3' ) selected="" @endif >已审核</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<button name="submit" id="submit_btn" class="btn btn-primary btn-block" type="submit">提 交</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
