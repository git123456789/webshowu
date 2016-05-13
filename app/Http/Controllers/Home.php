<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Articles;
use App\Websites;
use App\Categories;
use Validator, Redirect, DB, Session, phpQuery;

class Home extends Controller
{
	public $users;

	public function __construct(){
		$username = Session::get('username');
		$user = User::where('user_email', $username)->first();
		$user->login_ip = long2ip($user->login_ip);
		$this->users = $user;
	}
    //会员中心
	public function index(Request $request){
		$data['pagename'] = '会员中心';
		$data['site_title'] = '会员中心 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$user_types = array('admin'=>'管理员','member'=>'注册会员','recruit'=>'快速收录','vip'=>'VIP会员');
		$data['myself'] = $this->users;
		$data['myself']['user_types'] = $user_types[$this->users->user_type];
		$data['myself']['website'] = Websites::where('user_id', '=', $this->users->user_id)->count();
		$data['myself']['article'] = Articles::where('user_id', '=', $this->users->user_id)->count();
		return view('home/index',$data);
	}
	//站点列表
	public function get_site(Request $request){
		$websites = Websites::where('user_id','=',$this->users->user_id)->orderBy('updated_at','desc')->paginate(15);
		$data['pagename'] = '网站管理';
		$data['site_title'] = '网站管理 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['websites'] = $websites;
		$data['myself'] = $this->users;
		return view('home/site',$data);
	}
	//站点添加
	public function get_site_add(Request $request){
		$data['category_option'] = $this->get_category_option('webdir', 0, 0, 0);
		$data['pagename'] = '网站提交';
		$data['site_title'] = '网站提交 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		return view('home/site_add',$data);
	}
	//站点添加
	public function post_site_add(Request $request){
		$rules = [
			'web_url' => 'required|active_url',
			'web_name' => 'required',
			'cate_id' => 'required',
			'web_intro' => 'required',
		];
		$messages = [
			'web_url.required' => '请输入网站域名！',
			'web_url.active_url' => '请输入正确的网站域名！',
			'web_name.required' => '请输入网站名称！',
			'cate_id.required' => '请选择网站所属分类！',
			'web_intro.required' => '请输入网站简介！',
		];
        $validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
		$cate = Categories::where('cate_id', $request->cate_id)->first();
		if ($cate['cate_childcount'] > 0) {
			return redirect::back()->withErrors('指定的分类下有子分类，请选择子分类进行操作！');
		}
		if(!empty($request->web_tags)) {
			$request->web_tags = str_replace('/', ',', $request->web_tags);
			$request->web_tags = str_replace('|', ',', $request->web_tags);
			$request->web_tags = str_replace('|', ',', $request->web_tags);
			$request->web_tags = str_replace('，', ',', $request->web_tags);
			$request->web_tags = str_replace(',,', ',', $request->web_tags);
			if (substr($request->web_tags, -1) == ',') {
				$request->web_tags = substr($request->web_tags, 0, strlen($request->web_tags) - 1);
			}
		}
		$weburl = Websites::where('web_url', $request->web_url)->first();
		if($weburl) {
			return redirect::back()->withErrors('您所提交的网站已存在！');
		}
		$web_ip = sprintf("%u", ip2long($request->getClientIp()));
		$data = new Websites;
		$data->cate_id = $request->cate_id;
		$data->user_id = $this->users->user_id;
		$data->web_name = $request->web_name;
		$data->web_url = $request->web_url;
		$data->web_tags = $request->web_tags;
		$data->web_intro = $request->web_intro;
		$data->web_ip = $web_ip;
		$data->web_grank = $request->web_grank;
		$data->web_brank = $request->web_brank;
		$data->web_srank = $request->web_srank;
		$data->web_arank = $request->web_arank;
		$data->web_status = '2';
		$data->save();
		return redirect('/site');
	}
	//站点编辑
	public function get_site_edit(Request $request){
		$web = DB::table('websites')->where('websites.web_id','=',$request->id)->first();
		$data['category_option'] = $this->get_category_option('webdir', 0, $web->cate_id , 0);
		$data['pagename'] = '网站编辑';
		$data['site_title'] = '网站编辑 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		$data['edit_id'] = $request->id;
		$data['web'] = $web;
		return view('home/site_edit',$data);
	}
	//站点编辑
	public function post_site_edit(Request $request){
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
			$request->web_tags = str_replace('/', ',', $request->web_tags);
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
		$web_site['web_status'] = '2';
		Websites::where('web_id', $request->edit_id)->update($web_site);
		return redirect('/site');
	}
	//文章列表
	public function get_art(Request $request){
		$articles = Articles::where('user_id','=',$this->users->user_id)->orderBy('updated_at','desc')->paginate(15);
		$data['pagename'] = '文章管理';
		$data['site_title'] = '文章管理 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['articles'] = $articles;
		$data['myself'] = $this->users;
		return view('home/art',$data);
	}
	//文章添加
	public function get_art_add(Request $request){
		$data['category_option'] = $this->get_category_option('article', 0, 0, 0);
		$data['pagename'] = '发布文章';
		$data['site_title'] = '发布文章 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		return view('home/art_add',$data);
	}
	//文章添加
	public function post_art_add(Request $request){
		$rules = [
			'art_title' => 'required',
			'art_tags' => 'required',
			'art_intro' => 'required',
			'art_content' => 'required',
		];
		$messages = [
			'art_title.required' => '请输入文章标题',
			'art_tags.required' => '请输入TAG标签',
			'art_intro.required' => '请输入内容摘要',
			'art_content.required' => '请输入文章内容',
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
			$request->art_tags = str_replace('/', ',', $request->art_tags);
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
		$art_title = Articles::where('art_title', $request->art_title)->first();
		if($art_title) {
			return redirect::back()->withErrors('您所发布的文章已存在！');
		}
		$data = new Articles;
		$data->user_id = $this->users->user_id;
		$data->cate_id = $request->cate_id;
		$data->art_title = $request->art_title;
		$data->art_tags = $request->art_tags;
		$data->copy_from = $request->copy_from;
		$data->copy_url = $request->copy_url;
		$data->art_intro = $request->art_intro;
		$data->art_content = $request->art_content;
		$data->art_status = '2';
		$data->save();
		return redirect('/art');
	}
	//文章编辑
	public function get_art_edit(Request $request){
		$row = Articles::where('art_id', $request->id)->first();
		$data['category_option'] = $this->get_category_option('article', 0, $row->cate_id, 0);
		$data['pagename'] = '编辑文章';
		$data['site_title'] = '编辑文章 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		$data['edit_id'] = $request->id;
		$data['row'] = $row;
		return view('home/art_edit',$data);
	}
	//文章编辑
	public function post_art_edit(Request $request){
		$rules = [
			'art_title' => 'required',
			'art_tags' => 'required',
			'art_intro' => 'required',
			'art_content' => 'required',
		];
		$messages = [
			'art_title.required' => '请输入文章标题',
			'art_tags.required' => '请输入TAG标签',
			'art_intro.required' => '请输入内容摘要',
			'art_content.required' => '请输入文章内容',
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
			$request->art_tags = str_replace('/', ',', $request->art_tags);
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
		$art_data['art_status'] = '2';
		Articles::where('art_id', $request->edit_id)->update($art_data);
		return redirect('/art');
	}
	//个人资料
	public function get_profile(Request $request){
		if($request->edit_id){
			$art_data['nick_name'] = $request->nick_name;
			$art_data['user_qq'] = $request->user_qq;
			User::where('user_id', $this->users->user_id)->update($art_data);
			return redirect::to('profile')->with('success','个人资料修改成功！');
		}
		$data['pagename'] = '个人资料';
		$data['site_title'] = '个人资料 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		return view('home/profile',$data);
	}
	//修改密码
	public function get_editpwd(Request $request){
		if($request->edit_id){
			$rules = [
				'old_pass' => 'required',
				'new_pass' => 'required',
				'new_pass1' => 'required',
			];
			$messages = [
				'old_pass.required' => '请输入原始密码',
				'new_pass.required' => '请输入新 密 码',
				'new_pass1.required' => '再次输入新 密 码',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator);
			}
			if(md5($request->old_pass) == $this->users->user_pass){
				return Redirect::back()->withErrors('您输入的原始密码不正确！');
			}
			if($request->new_pass != $request->new_pass1){
				return Redirect::back()->withErrors('两次密码输入不一致，请重新输入！');
			}
			$art_data['user_pass'] = md5($request->new_pass);
			User::where('user_id', $this->users->user_id)->update($art_data);
			return redirect::to('editpwd')->with('success','账户密码修改成功！');
		}
		$data['pagename'] = '修改密码';
		$data['site_title'] = '修改密码 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '';
		$data['site_description'] = '';
		$data['myself'] = $this->users;
		$data['success'] = '';
		return view('home/editpwd',$data);
	}
	//退出登陆
	public function logout(Request $request){
		Session::flush();
        return redirect('/');
	}
	//处理ajax请求
	public function ajaxget(Request $request){
		/** crawl */
		if ($request->type == 'crawl') {
			$array = array();
			$rules = [
			'url' => 'required|active_url',
			];
			$messages = [
				'url.required' => '请输入网站域名！',
				'url.active_url' => '请输入正确的网站域名！',
			];
			$this->validate($request, $rules, $messages);
			$meta = $this->get_sitemeta($request->url);
			$data = $this->get_sitedata($request->url);
			if($data){
				$array['web_ip']	= $data['web_ip'];
				$array['web_grank']	= $data['web_grank'];
				$array['web_brank']	= $data['web_brank'];
				$array['web_srank']	= $data['web_srank'];
				$array['web_arank']	= $data['web_arank'];
				unset($data);
			}
			if($meta){
				$array['web_name']	= $meta['title'];
				$array['web_tags']	= $meta['keywords'];
				$array['web_intro']	= $meta['description'];
				unset($meta);
			}
			$array['code']	= '200';
			return response()->json($array);
		}
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
	//获取网站附属信息
	function get_sitedata($url){
		$array = array();
		//获取alexa pr
		$alexa = $this->get_url_content("http://xml.alexa.com/data?cli=10&dat=nsa&ver=quirk-searchstatus&url=$url");
		if (preg_match('/<POPULARITY[^>]*URL[^>]*TEXT[^>]*\"([0-9]+)\"/i', $alexa, $matches)) {
			$array['web_arank'] = strip_tags($matches[1]);
		} else {
			$array['web_arank'] = 0;
		}
		
		//获取sogou pr
		$sogou = $this->get_url_content("http://rank.ie.sogou.com/sogourank.php?ur=$url");
		if (preg_match('/sogourank=(\d+)/i', $sogou, $matches)) {
			$array['web_srank'] = intval($matches[1]);
		} else {
			$array['web_srank'] = 0;
		}

		//获取baidu pr
		$aizhan = $this->get_baidu_aizhan($url);
		$chinaz = $this->get_baidu_chinaz($url);
		if($aizhan > $chinaz){
			$array['web_brank'] = $aizhan;
		}else{
			$array['web_brank'] = $chinaz;
		}

		//获取google pr
		$google = $this->get_url_content("http://pr.links.cn/getpr.asp?queryurl=$url&show=1");
		if (preg_match('/<a(.*?)>(\d+)<\/a>/i', $google, $matches)) {
			$array['web_grank'] = intval($matches[2]);
		} else {
			$array['web_grank'] = 0;
		}
		//获取url地址ip
		if (preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $domain)) {
			$gethostby = $domain[2];
			$array['web_ip'] = gethostbyname($gethostby);
		}
		return $array;
	}
	/** 获取META信息 */
	function get_sitemeta($url) {
		$url	= $this->get_url_content($url);
		if($url){
			$html = phpQuery::newDocumentHTML($url); 
			$array = array();
			foreach(pq('meta') as $meta){
				$key = pq($meta)->attr('name');
				$value= pq($meta)->attr('content');
				$array[strtolower($key)] = $value;
			}
			$array['title'] = pq('title')->text();
			unset($html);
			return $array;
		}
		return false;
	}
	/** 获取指定URL内容 */
	function get_url_content($url) {
		$timeout = 30;
		$data = '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec($ch);
		if (!$data) {
			return false;
		} else {
			$encode = mb_detect_encoding($data, array('ascii', 'gb2312', 'utf-8', 'gbk'));
			if ($encode == 'EUC-CN' || $encode == 'CP936') {
				$data = @mb_convert_encoding($data, 'utf-8', 'gb2312');
			}
			return $data;
		}
	}
	function get_baidu_aizhan($url) {
		$data = $this->get_url_content("http://www.aizhan.com/getbr.php?url=$url&style=1");
		if (preg_match('/<a(.*?)>(\d+)<\/a>/i', $data, $matches)) {
			$rank = intval($matches[2]);
		} else {
			$rank = 0;
		}
		return $rank;
	}
	function get_baidu_chinaz($url) {
		$data = $this->get_url_content("http://mytool.chinaz.com/baidusort.aspx?host=$url&sortType=0");
		if (preg_match("/<font(.*?)>(\d+)<\/font>/i", $data, $matches)) {
			$rank = intval($matches[2]);
		} else {
			$rank = 0;
		}
		return $rank;
	}
}
