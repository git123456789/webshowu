<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\ArtCaiji;
use DB, phpQuery, Log;

class RegList extends Job implements ShouldQueue
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
        Log::info('调用了一次==='.$this->row->reg_url);
        $html = $this->get_url_content($this->row->reg_url);
        if($html){
            $data = array();
            phpQuery::newDoclamb($html);
            foreach(pq($this->row->reg_list) as $res){
                $title = trim(pq($res)->text());
                if($this->row->reg_ishost){
                    $href = $this->row->reg_host.pq($res)->attr('href');
                }else{
                    $href = pq($res)->attr('href');
                }
                $data[] = $title.'======'.$href;
            }
            phpQuery::$documents = array();
            if($this->row->reg_content == json_encode($data)){
                //Log::info('采集结果一样=='.$this->row->reg_url);
            }else{
                $result = $this->ArtCaiji_array_diff($data,json_decode($this->row->reg_content,true));
                if($result){
                    DB::table('Reg_List')->where('reg_id',$this->row->reg_id)->update(array('updated_at'=> date('Y-m-d H:i:s',time()),'reg_content'=>json_encode($data)));
                    $this->ArtCaiji_queue($result,$this->row->cate_id);
                    return true;
                }
            }
        }else{
            Log::info('这个网址已经失去效果=='.$this->row->reg_url);
        }
    }
    /*文章一维数组进行对比返回不一样的值*/
    function ArtCaiji_array_diff($array1,$array2){
        if($array2!='' && $array1!=''){
            $result = array_diff($array1,$array2);
            return $result;
        }
        if($array2 == '' && $array1 !=''){}
        {
            $result = $array1;
            return $result;
        }
        return false;
    }
    /*文章队列提交*/
    function ArtCaiji_queue($array,$cate_id){
        if($array){
            foreach ($array as $value) {
                $str = explode('======',$value);
                $result['title'] = $str['0'];
                $result['href'] = $str['1'];
                $result['cate_id'] = $cate_id;
                if($result['title'] != '' && $result['href'] != '' && $result['cate_id']){
                    dispatch(new ArtCaiji($result));
                }
            }
            return true;
        }else{
            return true;
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
}
