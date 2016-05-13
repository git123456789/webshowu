<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Articles;
use App\Websites;
use App\Links;
use App\Categories;
use App\Pagelist;
use App\Lables;
use App\Reg_List;
use App\Jobs\Xiumei;
use App\Jobs\WebThum;
use App\Jobs\ArtCaiji;
use DB , Agent, phpQuery, Storage;
class indexController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    //首页
	function index(Request $request){
		$data['site_title'] = '中文分类目录|网站分类目录|免费网站目录|dmoz目录-秀站分类目录分享网站价值_北京儒尚科技有限公司';
		$data['site_keywords'] = 'DMOZ目录,DMOZ分类目录,网站收录,网站目录,网站登录,中文网站目录,秀站分类目录,分类目录,秀站分类目录分享网站价值,秀站,秀站目录,免费网站目录';
		$data['site_description'] = '秀站分类目录免费收录各类优秀中文网站，提供网站分类目录检索，关键字搜索，提供网站即可免费快速提升网站流量，分享网站价值也是中国dmoz的标志';
		$data['articles'] = Articles::where(['art_status'=>'3'])->orderBy('art_id','desc')->take('10')->get();
		$data['websites'] = Websites::where(['web_status'=>'3'])->orderBy('web_isbest','desc')->orderBy('updated_at','desc')->take('10')->get();
		$data['hotsites'] = Websites::where(['web_status'=>'3'])->orderBy('updated_at','desc')->take('10')->get();
		$data['links'] = Links::where(['link_hide'=>'1'])->orderBy('link_order','asc')->get();
		$data['lables'] = Lables::where(['cate_mod'=>'webdir'])->orderBy('lab_views','desc')->take('30')->get();
		$data['cates'] = $this->cates();
		$data['pages'] = Pagelist::get();
		$data['mobile'] = Agent::isMobile()?'1':'0';//判断访问者是手机还是pc
		$data['success'] = 'xiumei';
		$success = $this->xiumei_proxy($request);
		if($success){
			$data['success'] = $success;
		}
		return view('front/index',$data);
	}
	//标签
	function tags(Request $request){
		$lables = Lables::where('lab_name',$request->str )->first();
		if($lables){
			$data['site_title'] = $lables->lab_name.'-秀站分类目录分享网站价值';
			$data['site_keywords'] = $lables->lab_tags.',秀站分类目录';
			$data['site_description'] = $lables->lab_intro.'-秀站分类目录分享网站价值';
			$data['lablist'] = Websites::where(['web_status'=>'3'])->where('web_tags','like',"%$lables->lab_name%")->paginate(15);
			$data['pages'] = Pagelist::get();
			$webarray['lab_views'] = $lables->lab_views+1;
			Lables::where('lab_name',  $request->str )->update($webarray);
			return view('front/tags',$data);
		}else{
			return redirect('/');
		}
	}
	//帮助中心
	function diypage(Request $request){
		$data['page_first'] = Pagelist::where('page_id',$request->id)->first();
		if($data['page_first']){
			$data['site_title'] = $data['page_first']->page_name.'-秀站分类目录分享网站价值';
			$data['site_keywords'] = $data['page_first']->page_name.'DMOZ目录,DMOZ分类目录,网站目录,中文网站目录,秀站分类目录,分类目录,秀站,秀站目录,免费网站目录';
			$data['site_description'] = $data['page_first']->page_name;
			$data['pages'] = Pagelist::get();
			$data['mobile'] = Agent::isMobile()?'1':'0';//判断访问者是手机还是pc
			return view('front/diypage',$data);
		}else{
			return redirect('/');
		}
	}
	//递归分类目录
	function cates(){
		$array = array();
		$cate = Categories::where(['cate_isbest'=>'1'])->orderBy('cate_order','asc')->orderBy('cate_id','asc')->get();
		foreach($cate as $str){
			$cate_data['cate_name'] = $str->cate_name;
			$cate_data['cate_id'] = $str->cate_id;
			$collects = explode(",",$str->cate_arrchildid);
			$cate_data['site_array'] = DB::table('websites')->leftJoin('users', 'users.user_id','=','websites.user_id')->where('websites.web_status','3')->where('websites.web_ispay','1')->whereIn('websites.cate_id',$collects)->orderBy('websites.updated_at','desc')->take('6')->get();
			$array[] = $cate_data;
		}
		return $array;
	}
	//基于反向链接
	function xiumei_proxy($request){
		if($request->server('HTTP_REFERER') && !str_contains($request->server('HTTP_REFERER'),'webshowu')){
			$app_url = $this->xiumei_parse_url($request->server('HTTP_REFERER'));
			$array = Websites::where('web_url','like','%'.$app_url.'%')->first();
			if(!$array){
				if($this->get_url_content('http://'.$app_url)){
					$this->xiumei_add($app_url);
					$this->dispatch(new Xiumei($app_url));
					return '基于反向链接已经把网址：'.$app_url.'添加到列队中……请稍等！秀妹正在给您处理中……';
				}else{
					return '您的网站秀妹访问不到！及时处理请加入QQ群：57176386';
				}
			}else{
				if($array['web_status'] == '1'){
					$this->dispatch(new Xiumei($app_url));
					if($array['web_ispay'] == '0'){
						return '秀站在'.$array['updated_at'].'处理过您的站点发现没有秀站分类目录的友情链接&nbsp;请把链接添加上，秀妹会给你下一步处理的';
					}
					return '秀妹正在获取您站点：'.$app_url.'的信息&nbsp;请稍等……';
				}else{
					$websites = Websites::where('web_id',$array->web_id)->first();
					$webarray['web_views'] = $websites->web_views+1;
					Websites::where('web_id', $websites->web_id)->update($webarray);
					return false;
				}
			}
		}
		return false;
	}
	function xiumei_add($url){
		$data = new Websites;
		$data->cate_id = '10000';
		$data->user_id = '10000';
		$data->web_name = '';
		$data->web_url = $url;
		$data->web_tags = '';
		$data->web_intro = '';
		$data->web_status = '1';
		$data->save();
	}
	function xiumei_parse_url($url,$type=0){
		if($type == 0){
			$array = parse_url($url);
			return $array['host'];
		}	
		return false;
	}
	//测试功能
	function ceshi(Request $request){
		$dom = $this->get_url_content('http://www.5118.com/toutiao/APPyunying',true);
		echo $dom;
	}
	/** 获取指定URL内容 */
	function get_url_content($url, $proxy = true) {
        $data = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER ,0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // 设置不将爬取代码写到浏览器，而是转化为字符串
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if($proxy){
            //AppKey 信息，请替换
            $appKey = '160657822';
            //AppSecret 信息，请替换
            $secret = 'fd4f8fdd0ab2ccde7dab3cf93627d186';
            //示例请求参数
            $paramMap = array(
                'app_key'   => $appKey,
                'timestamp' => date('Y-m-d H:i:s'),
                'enable-simulate' => 'false',
            );
            //按照参数名排序
            ksort($paramMap);
            //连接待加密的字符串
            $codes = $secret;
            //请求的URL参数
            $auth = 'MYH-AUTH-MD5 ';
            foreach ($paramMap as $key => $val) {
                $codes .= $key . $val;
                $auth  .= $key . '=' . $val . '&';
            }
            $codes .= $secret;
            //签名计算
            $auth .= 'sign=' . strtoupper(md5($codes));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Proxy-Authorization: {$auth}"));
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($ch, CURLOPT_PROXY, '123.56.251.212'); //代理服务器地址
            curl_setopt($ch, CURLOPT_PROXYPORT, '8123'); //代理服务器端口
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        $data = curl_exec($ch);
        if ($data  === FALSE) {
            return false;
        }
        curl_close($ch);
        if (!$data) {
            return false;
        } else {
            $encode = mb_detect_encoding($data, array('ascii', 'gb2312', 'utf-8', 'gbk'));
            if($encode != 'utf-8'){
                if($encode == 'EUC-CN' || $encode == 'CP936'){
                    $data = @mb_convert_encoding($data, 'utf-8', 'gb2312');
                }else{
                    $data = @mb_convert_encoding($data, 'utf-8', $encode);
                }   
            }
            return $data;
        }
    }

	function Two_One_array($array){
		$result = array();
		foreach ($array as $str) {
			$result[] = $str['title'].'======'.$str['href'];
		}
		return $result;
	}

	function array_diff_assoc2_deep($array1, $array2) {
		$array = [];
        $num = count($array1)>count($array2)?count($array1):count($array2);
        for($i=0;$i<$num;$i++){
        	if($array1[$i]['title'] != $array2[$i]['title']){

        	}
        }
	} 
	
	//采集百度热点
	function redian(){
		$array = array();
		$array = array();
		phpQuery::newDocumentFileHTML('http://top.baidu.com/buzz?b=341&c=513&fr=topbuzz_b1_c513','utf-8');
		$artlist = pq('.item-headline');
		foreach($artlist as $str){
			$boxs['title'] = pq($str)->find('a')->text();
			$boxs['p'] = pq($str)->find('p')->text();
			$array[] = $boxs;
		}
		print_r($array);
	}
	//seo生成sitmap
	function get_sitemap(){
		
	}
	
}
