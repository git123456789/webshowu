<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Websites;
use App\Articles;
use App\Categories;
use App\Pagelist;
use DB, Agent;

class Webdir extends Controller
{
    //推荐资讯
	public $art_list;
	//推荐站点
	public $site_list;
	public function __construct(){
		$this->art_list = Articles::where(['art_status'=>'3'])->orderBy('art_views','desc')->take('10')->get();
		//$this->art_list = Articles::where(['art_isbest'=>'1','art_status'=>'3'])->orderBy('updated_at','desc')->take('10')->get();
		$this->site_list = Websites::where(['web_isbest'=>'1','web_status'=>'3'])->orderBy('updated_at','desc')->take('10')->get();
	}
	//站点列表
	function lists(Request $request){
		if($request->id){
			$cate = Categories::where('cate_id', $request->id)->first();
			$collects = explode(",",$cate->cate_arrchildid);
			$websites = Websites::where('web_status','3')->whereIn('cate_id',$collects)->orderBy('web_id','desc')->paginate(15);
			$data['websites'] = $websites;
			$data['site_title'] = $cate->cate_name.'_'.'秀目录_网站目录_网站分类目录_网站目录_分类目录';
			$data['site_keywords'] = $cate->cate_keywords.','.'秀目录,网站目录,免费网站目录,分类目录,网站分类目录';
			$data['site_description'] = $cate->cate_description.','.'秀目录网站目录是全人工编辑的开放式网站分类目录，收录国内外、各行业优秀网站，免费网站目录,网站分类目录, 网站提交入口。旨在为用户提供优秀网站目录检索、网站推广服务。';
		}else{
			$websites = Websites::where('web_status','=','3')->orderBy('web_id','desc')->paginate(15);
			$data['websites'] = $websites;
			$data['site_title'] = '秀目录_网站目录_网站分类目录_网站目录_分类目录';
			$data['site_keywords'] = '秀目录,网站目录,免费网站目录,分类目录,网站分类目录';
			$data['site_description'] = '秀目录网站目录是全人工编辑的开放式网站分类目录，收录国内外、各行业优秀网站，免费网站目录,网站分类目录, 网站提交入口。旨在为用户提供优秀网站目录检索、网站推广服务。';
		}
		$data['pages'] = Pagelist::get();
		$data['art_list'] = $this->art_list;
		$data['site_list'] = $this->site_list;
		return view('front/webdir_lists',$data);
	}
	//站点内容页
	function info(Request $request){
		$websites = DB::table('websites')
		->leftJoin('users', 'users.user_id','=','websites.user_id')
		->where('websites.web_status','=','3')
		->where('websites.web_id','=',$request->id)
		->select('websites.*','users.user_qq')
		->first();
		if($websites){
			$data['websites'] = $websites;

			$data['site_title'] = $websites->web_name.$websites->web_url.' - 秀站分类目录分享网站价值';
			$data['site_keywords'] = $websites->web_name.','.$websites->web_tags;
			$data['site_description'] = $websites->web_name.','.$websites->web_intro;

			$data['art_list'] = $this->art_list;
			$data['site_list'] = $this->site_list;

			$data['mobile'] = Agent::isMobile()?'1':'0';//判断访问者是手机还是pc
			$data['webtags'] = explode(',',$websites->web_tags);
			$data['pages'] = Pagelist::get();
			$webarray['web_views'] = $websites->web_views+1;
			Websites::where('web_id', $request->id)->update($webarray);
			return view('front/webdir_info',$data);
		}else{
			return redirect('/');
		}	
	}
}
