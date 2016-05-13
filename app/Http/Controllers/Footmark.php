<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Websites;
use App\Articles;
use App\User;
use App\Pagelist;
use DB , Agent;
class Footmark extends Controller
{
    //
	function info(Request $request){
		if(!$request->id){
			return redirect('/');
		}

		$users = User::where('user_id', $request->id )->first();
		if($users){
			$data['site_title'] = $users->nick_name.'的秀足迹 - 秀站分类目录分享网站价值';
			$data['site_keywords'] = $users->nick_name.',秀足迹,'.$users->user_qq;
			$data['site_description'] = $users->nick_name.'的秀足迹 - 秀站分类目录分享网站价值';
			$data['users'] = $users;
			$data['lists'] = $this->lists($request->id);
			$data['pages'] = Pagelist::get();
			return view('front/footmark_info',$data);
		}else{
			return redirect('/');
		}
	}

	function lists($uid){
		$lists = array();
		$Articles = Articles::where(['user_id'=>$uid,'art_status'=>'3'])->orderBy('updated_at','desc')->get();
		foreach($Articles as $str){
			$data['id'] = $str->art_id;
			$data['title'] = $str->art_title;
			$data['intro'] = $str->art_intro;
			$data['updated_at'] = $str->updated_at;
			$data['type'] = 'art';
			$data['img'] = '';
			$lists[] = $data;
		}
		$Websites = Websites::where(['user_id'=>$uid,'web_status'=>'3'])->orderBy('updated_at','desc')->get();
		foreach($Websites as $str){
			$data['web_url'] = $str->web_url;
			$data['id'] = $str->web_id;
			$data['title'] = $str->web_name;
			$data['intro'] = $str->web_intro;
			$data['updated_at'] = $str->updated_at;
			$data['type'] = 'img';
			$data['img'] = $str->web_pic;
			$lists[] = $data;
		}
		$lists = $this->arraySort($lists,'updated_at','desc');
		return $lists;
	}

	function arraySort($arr, $keys, $type = 'asc') {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v){
            $keysvalue[$k] = $v[$keys];
        }
        $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
           $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}
