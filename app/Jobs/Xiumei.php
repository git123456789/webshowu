<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB, phpQuery, Storage, Log;

class Xiumei extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
	
	protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        //
		$this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
		if($this->url){
			$websites = DB::table('websites')->where('web_url','like', '%'.$this->url.'%' )->first();
			if($websites){
				$step = '';
				$file = $this->get_url_content('http://'.$this->url);
				if($file){
					$documentID = phpQuery::newDoclamb($file);
				}else{
					return false;
				}		
				if($documentID){
					if(false){
						$step = '1';
					}else{
						foreach(pq('body a') as $str){
							$href = str_contains(pq($str)->attr('href'),'webshowu.com');
							if($href){
								$step = '1';
							}
						}
					}
				}else{
					Log::info('phpquery没有抓取到网站域名=='.$this->url);
					return true;
				}
				if($step == '1'){
					$array = array();
					foreach(pq('meta') as $meta){
						$key = pq($meta)->attr('name');
						$value= pq($meta)->attr('content');
						$array[strtolower($key)] = $value;
					}
					$array['title'] = pq('title')->text();
					if(!empty($array['keywords'])) {
						$array['keywords'] = str_replace('|', ',', $array['keywords']);
						$array['keywords'] = str_replace('|', ',', $array['keywords']);
						$array['keywords'] = str_replace('，', ',', $array['keywords']);
						$array['keywords'] = str_replace(',,', ',', $array['keywords']);
						if (substr($array['keywords'], -1) == ',') {
							$array['keywords'] = substr($array['keywords'], 0, strlen($array['keywords']) - 1);
						}
					}else{
						$array['keywords'] = $array['title'];
					}

					if(!empty($array['description'])){
						$array['description'] = $array['description'];
					}else{
						$array['description'] = $this->description(pq('body')->text());
					}
					
					$filename = 'xiumei/'.date('Ymd',time()).'/'.$this->url.'.jpg';
					$this->getimg($this->url,$filename);
					$data = $this->get_sitedata($this->url);

					$web_site['web_name']		= $array['title'];
					$web_site['web_url']		= 'http://'.$this->url;
					$web_site['web_pic']		= $filename;
					$web_site['web_tags']		= $array['keywords'];
					$web_site['web_intro']		= $array['description'];
					$web_site['web_ispay']		= '1';
					$web_site['web_ip']			= $data['web_ip'];
					$web_site['web_grank']		= $data['web_grank'];
					$web_site['web_brank']		= $data['web_brank'];
					$web_site['web_srank']		= $data['web_srank'];
					$web_site['web_arank']		= $data['web_arank'];
					$web_site['web_status']		= '3';
					$web_site['updated_at']		= date('Y-m-d H:i:s',time());
					DB::table('websites')->where('web_id', $websites->web_id )->update($web_site);
					phpQuery::$documents = array();
					return true;
				}else{
					Log::info('没有找到友情链接！=='.$this->url);
					return true;
				}

			}else{
				Log::info('没有在数据库找到域名！=='.$this->url);
				return true;
			}
		}else{
			Log::info('没有接收到网站域名');
			return true;
		}
    }
	//获取网站简介
	function description($str)
	{
		$str = trim($str);
		$str = str_replace(array("　","\rn", "\r", "\n", " \", ", "\t", "\o", "\x0B"," "),"", $str);
		$str = str_limit($str,200,'');
		return $str;
	}
	//获取网站缩略图
	function getimg($url = "", $filename = "")
	{
		$url = 'http://images.shrinktheweb.com/xino.php?stwembed=1&stwaccesskeyid=11f8f5f6ceae538&stwsize=xlg&stwurl='.$url;
		$ch =curl_init(); 
		$timeout =60; 
		curl_setopt($ch,CURLOPT_URL,$url); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
		$img=curl_exec($ch); 
		curl_close($ch);
		Storage::put($filename, $img);
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
	/** 获取指定URL内容 */
	function get_url_content($url, $proxy = false) {
		$timeout = 30;
		$data = '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER ,0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); // 设置不将爬取代码写到浏览器，而是转化为字符串
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($proxy){
			curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
			curl_setopt($ch, CURLOPT_PROXY, '125.71.133.38'); //代理服务器地址
			curl_setopt($ch, CURLOPT_PROXYPORT, 808); //代理服务器端口
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
		}
		$data = curl_exec($ch);
		if ($data  === FALSE) {
			Log::info('curl出现错误网址是=='.$url);
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
