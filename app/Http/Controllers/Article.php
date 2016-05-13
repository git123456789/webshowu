<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Websites;
use App\Articles;
use App\Categories;
use App\Pagelist;
use DB, Agent;

class Article extends Controller
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
	function index(Request $request){
		$data['cates'] = $this->cates();
		$data['site_title'] = '秀资讯--不一样的资讯网站';
		$data['site_keywords'] = '秀站长,秀seo,秀运营,秀技术,秀资讯,奇趣科技,不一样的网站';
		$data['site_description'] = '秀资讯是一个不一样的资讯网站，每天更新最新站长运营、SEO技术、奇趣科技的文章，是一个值得收藏的网站。';
		$data['pages'] = Pagelist::get();
		return view('front/article_index',$data);
	}

	function lists(Request $request){
		if(!is_numeric($request->id)){
			$cate = Categories::where('cate_dir', $request->id)->first();
			$collects = explode(",",$cate->cate_arrchildid);
			$articles = Articles::where('art_status','3')->whereIn('cate_id',$collects)->orderBy('art_id','desc')->paginate(15);
			$data['articles'] = $articles;
			$data['site_title'] = $cate->cate_name.'_'.'秀资讯--不一样的资讯网站';
			$data['site_keywords'] = $cate->cate_keywords.','.'秀站长,秀seo,秀运营,秀技术,秀资讯,奇趣科技,不一样的网站';
			$data['site_description'] = $cate->cate_description.','.'秀资讯是一个不一样的资讯网站，每天更新最新站长运营、SEO技术、奇趣科技的文章，是一个值得收藏的网站。';
		}else{
			$articles = Articles::where('art_status','3')->orderBy('art_id','desc')->paginate(15);
			$data['articles'] = $articles;
			$data['site_title'] = '秀资讯--不一样的资讯网站';
			$data['site_keywords'] = '秀站长,秀seo,秀运营,秀技术,秀资讯,奇趣科技,不一样的网站';
			$data['site_description'] = '秀资讯是一个不一样的资讯网站，每天更新最新站长运营、SEO技术、奇趣科技的文章，是一个值得收藏的网站。';
		}
		$data['pages'] = Pagelist::get();
		$data['art_list'] = $this->art_list;
		$data['site_list'] = $this->site_list;
		return view('front/article_lists',$data);
	}

	function info(Request $request){
		$articles = DB::table('articles')->where('articles.art_id','=',$request->id)->where('articles.art_status','=','3')->first();
		if($articles){
			$data['articles'] = $articles;
		
			$data['site_title'] = $articles->art_title.' - 秀站分类目录分享网站价值';
			$data['site_keywords'] = $articles->art_title.','.$articles->art_tags;
			$data['site_description'] = $articles->art_title.','.$articles->art_intro;
			
			$data['art_list'] = $this->art_list;
			$data['site_list'] = $this->site_list;

			$data['prev'] = $this->getPrevArticleId($request->id,$articles->cate_id);
			$data['next'] = $this->getNextArticleId($request->id,$articles->cate_id);
			$data['mobile'] = Agent::isMobile()?'1':'0';//判断访问者是手机还是pc
			$data['arttags'] = explode(',',$articles->art_tags);
			$data['pages'] = Pagelist::get();
			$art_data['art_views'] = $articles->art_views+1;
			Articles::where('art_id', $request->id)->update($art_data);
			return view('front/article_info',$data);
		}else{
			return redirect('/');
		}
		
	}

	//递归分类目录
	protected function cates(){
		$array = array();
		$cate = Categories::where('cate_mod','article')->orderBy('cate_id','desc')->select('cate_name','cate_dir','cate_id','cate_arrchildid')->get();
		foreach($cate as $str){
			$cate_data = $str;
			$collects = explode(",",$str->cate_arrchildid);
			$cate_data['site_array'] = Articles::where('art_status','3')->orderBy('art_id','desc')->whereIn('cate_id',$collects)->select('art_id','art_title as title','art_intro as intro','updated_at as uptime')->take('10')->get();
			$array[] = $cate_data;
		}
		return $array;
	}

	protected function getPrevArticleId($id,$cate_id){
		$aid = Articles::where('art_id', '<', $id)->where('art_status','=','3')->where('cate_id',$cate_id)->max('art_id');
		return DB::table('articles')->where('articles.art_id','=',$aid)->first();
	}

	protected function getNextArticleId($id,$cate_id){
		$aid = Articles::where('art_id', '>', $id)->where('art_status','=','3')->where('cate_id',$cate_id)->min('art_id');
		return DB::table('articles')->where('articles.art_id','=',$aid)->first();
	}
}
