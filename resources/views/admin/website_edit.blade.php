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
					<label class="col-sm-2 control-label" for="web_name">网站名称：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_name}}" placeholder="请输入网站名称" id="web_name" class="form-control" name="web_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_url">网站域名：</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="text" value="{{$web->web_url}}" placeholder="请输入网站域名" id="web_url" class="form-control" name="web_url">
						</label>
						<label class="radio-inline">
							<input type="button" value="抓取Meta" name="shangchuan" style="" class="btn btn-success" id="meta_btn" onclick="getmeta()">
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_tags">TAG标签：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_tags}}" placeholder="请输入TAG标签" id="web_tags" class="form-control" name="web_tags"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_intro">网站简介：</label>
					<div class="col-sm-10">
						<textarea placeholder="请填写网站简介" rows="3" id="web_intro" name="web_intro" class="form-control">{{$web->web_intro}}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_ip">服务器IP：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_ip}}" placeholder="请输入网站域名" id="web_ip" class="form-control" name="web_ip">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_grank">PageRank：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_grank}}" placeholder="请输入PageRank" id="web_grank" class="form-control" name="web_grank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_brank">BaiduRank：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_brank}}" placeholder="请输入BaiduRank" id="web_brank" class="form-control" name="web_brank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_srank">SogouRank：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_srank}}" placeholder="请输入web_srank" id="web_srank" class="form-control" name="web_srank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_arank">AlexaRank：</label>
					<div class="col-sm-10">
						<input type="text" value="{{$web->web_arank}}" placeholder="请输入AlexaRank" id="web_arank" class="form-control" name="web_arank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="inputEmail3">属性设置：</label>
					<div class="col-sm-10">
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="web_ispay" @if( $web->web_ispay =='1' ) checked="" @endif >付费&nbsp;&nbsp;
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="web_istop" @if( $web->web_istop =='1' ) checked="" @endif >置顶&nbsp;&nbsp;
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" value="1" name="web_isbest" @if( $web->web_isbest =='1' ) checked="" @endif >推荐&nbsp;&nbsp;
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_status">站点状态：</label>
					<div class="col-sm-10">
						<select name="web_status" class="form-control">
							<option value="1" @if( $web->web_status =='1' ) selected="" @endif >草稿</option>
							<option value="2" @if( $web->web_status =='2' ) selected="" @endif >待审核</option>
							<option value="3" @if( $web->web_status =='3' ) selected="" @endif >已审核</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<button name="submit" id="submit_btn" class="btn btn-primary btn-block" type="submit">提交</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
