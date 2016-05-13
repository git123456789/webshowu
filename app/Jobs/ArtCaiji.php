<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB, phpQuery, Log;

class ArtCaiji extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $row;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row)
    {
        //
        $this->row = $row;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $metas = array();
        $res    = array();
        if($this->row){
            $html = $this->get_url_content($this->row['href']);
            if($html){
                phpQuery::newDoclamb($html);
                foreach(pq('meta') as $meta){
                    $key = pq($meta)->attr('name');
                    $value= pq($meta)->attr('content');
                    $metas[strtolower($key)] = $value;
                }
                $title = trim(pq('.head-wrap .title')->text());
                $articles = DB::table('articles')->where('art_title', $title)->first();
                if($articles){
                    //Log::info('文章已经存在===='.$title);
                    phpQuery::$documents = array();
                    return true;
                }else{
                    $insertedId = DB::table('articles')->insertGetId([
                        'user_id' => '10000',
                        'cate_id' => $this->row['cate_id'],
                        'art_title' => $title,
                        'art_tags' => $metas['keywords'],
                        'copy_from' => '本站原创',
                        'copy_url' => 'http://www.webshowu.com',
                        'art_intro' => $metas['description'],
                        'art_content' => $this->ImgFindShift_5118(pq('.content')->html()),
                        'art_views' => '10',
                        'art_status' => '3',
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time()),
                    ]);
                    if($insertedId){
                        $result = $this->zhanzhang_push_baidu("http://www.webshowu.com/artinfo-".$insertedId.".html");
                    }
                }
                phpQuery::$documents = array();
                return true;
            }else{
                Log::info(var_export($this->row,true));
                return true;
            }
        }else{
            Log::info('数组是空值');
            return true;
        }
        
    }
    /*专业处理5118文章图片*/
    function ImgFindShift_5118($html){
        if($html){
            $array = array();
            $dochtml = phpQuery::newDoclamb($html);
            foreach(pq('img') as $img){
                $val['src1'] = pq($img)->attr('data-original');
                $val['src2'] = pq($img)->attr('src');
                $array[] = $val;
            }
            foreach($array as $key => $str){
                pq("img:eq($key)")->attr('src',$str['src1']);
            }
            return $dochtml;
        }else{
            return false;
        }
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

    function zhanzhang_push_baidu($url){

        $urls = array($url);
        $api = 'http://data.zz.baidu.com/urls?site=www.webshowu.com&token=6ujhg0alnRLbwZr7';
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }
    
}
