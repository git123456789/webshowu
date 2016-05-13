<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Articles;
use App\Categories;
use App\Pagelist;
use App\Websites;
use Validator, Redirect, DB, Session, phpQuery;

class Admin extends Controller
{
    //
	public $users;
	public function __construct(){
		$username = Session::get('username');
		$user = User::where('user_email', $username)->first();
		if($user->user_type != 'admin'){
			return redirect('/home');
		}
		$user->login_ip = long2ip($user->login_ip);
		$this->users = $user;
		$this->pages = Pagelist::paginate(15);
	}

	public function get_pagelist(Request $request){
		$pages = Pagelist::paginate(15);
		$data['pagename'] = '页面列表';
		$data['site_title'] = '页面列表 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['pages'] = $pages;
		$data['myself'] = $this->users;
		return view('admin/pages',$data);
	}

	public function get_pagelist_edit(Request $request){
		
	}
	//管理文章列表
	public function get_article(Request $request){
		$articles = Articles::where('art_status','=','2')->orderBy('updated_at','desc')->paginate(15);
		$data['pagename'] = '文章列表';
		$data['site_title'] = '文章列表 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['articles'] = $articles;
		$data['myself'] = $this->users;
		return view('admin/article',$data);
	}
	//编辑查看文章
	public function get_article_edit(Request $request){
		$row = Articles::where('art_id', $request->id)->first();
		$data['category_option'] = $this->get_category_option('article', 0, $row->cate_id, 0);
		$data['pagename'] = '审核文章';
		$data['site_title'] = '审核文章 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		$data['edit_id'] = $request->id;
		$data['row'] = $row;
		return view('admin/article_edit',$data);
	}
	//编辑文章内容
	public function post_article_edit(Request $request){
		$rules = [
			'art_title' => 'required',
			'art_tags' => 'required',
			'art_intro' => 'required',
			'art_content' => 'required',
			'art_views' => 'required|digits:2',
		];
		$messages = [
			'art_title.required' => '请输入文章标题',
			'art_tags.required' => '请输入TAG标签',
			'art_intro.required' => '请输入内容摘要',
			'art_content.required' => '请输入文章内容',
			'art_views.required' => '请输入浏览数',
			'art_views.digits' => '输入的数字不能超过2位数！',
		];
        $validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
		$cate = Categories::where('cate_id', $request->cate_id)->first();
		if ($cate['cate_childcount'] > 0) {
			return redirect::back()->withErrors('指定的分类下有子分类，请选择子分类进行操作！');
		}
		if (!empty($request->art_tags)) {
			$request->art_tags = str_replace('|', ',', $request->art_tags);
			$request->art_tags = str_replace('|', ',', $request->art_tags);
			$request->art_tags = str_replace('，', ',', $request->art_tags);
			$request->art_tags = str_replace(',,', ',', $request->art_tags);
			if (substr($request->art_tags, -1) == ',') {
				$request->art_tags = substr($request->art_tags, 0, strlen($request->art_tags) - 1);
			}
		}
		if (empty($request->copy_from)) $request->copy_from = '本站原创';
		if (empty($request->copy_url)) $request->copy_url = 'http://www.webshowu.com';
		$art_data['user_id'] = $this->users->user_id;
		$art_data['cate_id'] = $request->cate_id;
		$art_data['art_title'] = $request->art_title;
		$art_data['art_tags'] = $request->art_tags;
		$art_data['copy_from'] = $request->copy_from;
		$art_data['copy_url'] = $request->copy_url;
		$art_data['art_intro'] = $request->art_intro;
		$art_data['art_content'] = $request->art_content;
		$art_data['art_views'] = $request->art_views;
		$art_data['art_status'] = $request->art_status;
		Articles::where('art_id', $request->edit_id)->update($art_data);
		return redirect('/admin/article');
	}
	//管理站点列表
	public function get_website(Request $request){
		$websites = Websites::where('web_status','=','2')->orderBy('updated_at','desc')->paginate(15);
		$data['pagename'] = '站点列表';
		$data['site_title'] = '站点列表 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['websites'] = $websites;
		$data['myself'] = $this->users;
		return view('admin/website',$data);
	}
	//管理站点编辑
	public function get_website_edit(Request $request){
		$web = DB::table('websites')->where('websites.web_id','=',$request->id)->first();
		$data['category_option'] = $this->get_category_option('webdir', 0, $web->cate_id , 0);
		$data['pagename'] = '站点编辑';
		$data['site_title'] = '站点编辑 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		$data['edit_id'] = $request->id;
		$data['web'] = $web;
		return view('admin/website_edit',$data);
	}
	//管理站点编辑
	public function post_website_edit(Request $request){
		$rules = [
			'web_url' => 'required|active_url',
		];
		$messages = [
			'web_url.required' => '请输入网站域名！',
			'web_url.active_url' => '请输入正确的网站域名！',
		];
        $validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
		if(!$request->cate_id){
			return redirect::back()->withErrors('请选择网站所属分类！');
		}else{
			$cate = Categories::where('cate_id', $request->cate_id)->first();
			if ($cate['cate_childcount'] > 0) {
				return redirect::back()->withErrors('指定的分类下有子分类，请选择子分类进行操作！');
			}
		}
		if(empty($request->web_name)) {
			return redirect::back()->withErrors('请输入网站名称！');
		}
		
		if(!empty($request->web_tags)) {
			$request->web_tags = str_replace('|', ',', $request->web_tags);
			$request->web_tags = str_replace('|', ',', $request->web_tags);
			$request->web_tags = str_replace('，', ',', $request->web_tags);
			$request->web_tags = str_replace(',,', ',', $request->web_tags);
			if (substr($request->web_tags, -1) == ',') {
				$request->web_tags = substr($request->web_tags, 0, strlen($request->web_tags) - 1);
			}
		}
		if(empty($request->web_intro)) {
			return redirect::back()->withErrors('请输入网站简介！');
		}
		$web_ip = sprintf("%u", ip2long($request->getClientIp()));

		$web_site['cate_id'] = $request->cate_id;
		$web_site['user_id'] = $this->users->user_id;
		$web_site['web_name'] = $request->web_name;
		$web_site['web_url'] = $request->web_url;
		$web_site['web_tags'] = $request->web_tags;
		$web_site['web_intro'] = $request->web_intro;
		$web_site['web_ip'] = $request->web_ip;
		$web_site['web_grank'] = $request->web_grank;
		$web_site['web_brank'] = $request->web_brank;
		$web_site['web_srank'] = $request->web_srank;
		$web_site['web_arank'] = $request->web_arank;
		$web_site['web_status'] = $request->web_status;
		Websites::where('web_id', $request->edit_id)->update($web_site);
		return redirect('/admin/website');
	}
	/** category option */
	function get_category_option($cate_mod = 'webdir', $root_id = 0, $cate_id = 0, $level_id = 0) {
		if (!in_array($cate_mod, array('webdir', 'article'))) $cate_mod = 'webdir';
		$results = DB::select('SELECT cate_id, cate_name FROM categories WHERE root_id=? AND cate_mod=? ORDER BY cate_order ASC, cate_id ASC', [$root_id,$cate_mod]);
		$optstr = '';
		foreach ($results as $row) {
			$optstr .= '<option value="'.$row->cate_id.'"';
			if ($cate_id > 0 && $cate_id == $row->cate_id) $optstr .= ' selected';
			
			if ($level_id == 0) {
				$optstr .= ' style="background: #EEF3F7;">';
				$optstr .= '├'.$row->cate_name;
			} else {
				$optstr .= '>';
				for ($i = 2; $i <= $level_id; $i++) {
					$optstr .= '│&nbsp;&nbsp;';
				}
				$optstr .= '│&nbsp;&nbsp;├'.$row->cate_name;
			}
			$optstr .= '</option>';
			$optstr .= $this->get_category_option($cate_mod, $row->cate_id, $cate_id, $level_id + 1);
		}
		unset($results);
		return $optstr;
	}
}
