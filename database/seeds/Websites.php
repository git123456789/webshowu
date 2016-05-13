<?php

use Illuminate\Database\Seeder;

class Websites extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //调用一个数据库内容然后经过处理变成想要的数据插入到另外一个数据库里面
		
		$websites = DB::connection('webshowu')->select('select * from websites');		
		foreach($websites as $str){
			$webdata = DB::connection('webshowu')->table('webdata')->where('web_id',$str->web_id)->first();
			if($webdata){
				DB::table('websites')->insert([
					'web_id' => $str->web_id ,
					'user_id' => $str->user_id,
					'cate_id' => $str->cate_id,
					'web_name' => $str->web_name?$str->web_name:'null',
					'web_url' => $str->web_url?$str->web_url:'null',
					'web_tags' => $str->web_tags?$str->web_tags:'null',
					'web_pic' => $str->web_pic?$str->web_pic:'null',
					'web_intro' => $str->web_intro?$str->web_intro:'0',
					'web_ispay' => $str->web_ispay?$str->web_ispay:'0',
					'web_istop' => $str->web_istop?$str->web_istop:'0',
					'web_isbest' => $str->web_isbest?$str->web_isbest:'0',
					'web_islink' => $str->web_islink?$str->web_islink:'0',
					'web_ip' => $webdata->web_ip?$webdata->web_ip:'0',
					'web_grank' => $webdata->web_grank?$webdata->web_grank:'0',
					'web_brank' => $webdata->web_brank?$webdata->web_brank:'0',
					'web_srank' => $webdata->web_srank?$webdata->web_srank:'0',
					'web_arank' => $webdata->web_arank?$webdata->web_arank:'0',
					'web_instat' => $webdata->web_instat?$webdata->web_instat:'0',
					'web_outstat' => $webdata->web_outstat?$webdata->web_outstat:'0',
					'web_views' => $webdata->web_views?$webdata->web_views:'10',
					'web_status' => $str->web_status?$str->web_status:'0',
					'created_at' => $str->created_at?$str->created_at:date('Y-m-d H:i:s',time()),
					'updated_at' => $str->updated_at?$str->updated_at:date('Y-m-d H:i:s',time()),
				]);
			}else{
				DB::table('websites')->insert([
					'web_id' => $str->web_id ,
					'user_id' => $str->user_id,
					'cate_id' => $str->cate_id,
					'web_name' => $str->web_name?$str->web_name:'null',
					'web_url' => $str->web_url?$str->web_url:'null',
					'web_tags' => $str->web_tags?$str->web_tags:'null',
					'web_pic' => $str->web_pic?$str->web_pic:'null',
					'web_intro' => $str->web_intro?$str->web_intro:'0',
					'web_ispay' => $str->web_ispay?$str->web_ispay:'0',
					'web_istop' => $str->web_istop?$str->web_istop:'0',
					'web_isbest' => $str->web_isbest?$str->web_isbest:'0',
					'web_islink' => $str->web_islink?$str->web_islink:'0',
					'web_ip' => '0',
					'web_grank' => '0',
					'web_brank' => '0',
					'web_srank' => '0',
					'web_arank' => '0',
					'web_instat' => '0',
					'web_outstat' => '0',
					'web_views' => '10',
					'web_status' => $str->web_status?$str->web_status:'0',
					'created_at' => $str->created_at?$str->created_at:date('Y-m-d H:i:s',time()),
					'updated_at' => $str->updated_at?$str->updated_at:date('Y-m-d H:i:s',time()),
				]);
			}
		}
    }
}
