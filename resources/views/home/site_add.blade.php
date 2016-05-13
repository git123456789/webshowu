@extends('layouts.home')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<form class="form-horizontal" name="myfrom" id="myfrom" method="post" action="">
				<input type="hidden" value="POST" name="_method">
				<input type="hidden" value="{{ csrf_token() }}" name="_token" />
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
						<input type="text" value="" placeholder="请输入网站名称" id="web_name" class="form-control" name="web_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_url">网站域名：</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="text" value="" placeholder="例如：http://www.demo.com" id="web_url" class="form-control" name="web_url">
						</label>
						<label class="radio-inline">
							<input type="button" value="抓取Meta" name="shangchuan" style="" class="btn btn-success" id="meta_btn" onclick="getmeta()">
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_tags">TAG标签：</label>
					<div class="col-sm-10">
						<input type="text" value="" placeholder="请输入TAG标签" id="web_tags" class="form-control" name="web_tags"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_intro">网站简介：</label>
					<div class="col-sm-10">
						<textarea placeholder="请填写网站简介" rows="3" id="web_intro" name="web_intro" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_ip">服务器IP：</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="text" value="" placeholder="请输入网站域名" id="web_ip" class="form-control" name="web_ip">
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_grank">PageRank：</label>
					<div class="col-sm-10">
						<input type="text" value="" placeholder="请输入PageRank" id="web_grank" class="form-control" name="web_grank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_brank">BaiduRank：</label>
					<div class="col-sm-10">
						<input type="text" value="" placeholder="请输入BaiduRank" id="web_brank" class="form-control" name="web_brank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_srank">SogouRank：</label>
					<div class="col-sm-10">
						<input type="text" value="" placeholder="请输入web_srank" id="web_srank" class="form-control" name="web_srank"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="web_arank">AlexaRank：</label>
					<div class="col-sm-10">
						<input type="text" value="" placeholder="请输入AlexaRank" id="web_arank" class="form-control" name="web_arank"/>
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
