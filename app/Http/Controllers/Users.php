<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Validator, Redirect, Session, Captcha;
class Users extends Controller
{
    //登陆页面业务流程
	public function get_login(Request $request){
		$data['site_title'] = '登陆 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '登陆';
		$data['site_description'] = '登陆 - 秀站分类目录分享网站价值';
		return view('front/get_login',$data);
	}
	//登陆页面业务流程
	public function post_login(Request $request){
		$rules = [
			'username' => 'required',
            'password' => 'required',
		];
		$messages = [
			'username.required' => '请输入用户名',
			'password.required' => '请输入密码',
		];

        $validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
		$users = User::where('user_email', $request->input('username'))->first();
		if(!$users){
			return redirect::back()->withInput()->withErrors('您输入的用户名不存在……！');
		}
		if($users->user_pass != md5($request->input('password'))) {
			return redirect::back()->withInput()->withErrors('密码错误！请输入正确的密码……');
		}
		$request->session()->put('username', $request->input('username'));
		$data = array(
			'login_ip' => sprintf("%u", ip2long($request->getClientIp())),
			'login_count' => $users->login_count+1,
		);
		User::where('user_email',$request->input('username'))->update($data);
		return redirect('/home');
	}
	//注册页面业务流程
	public function get_register(Request $request){
		$data['site_title'] = '注册 - 秀站分类目录分享网站价值';
		$data['site_keywords'] = '注册';
		$data['site_description'] = '注册 - 秀站分类目录分享网站价值';
		return view('front/get_register',$data);
	}
	//注册页面业务流程
	public function post_register(Request $request){
		$rules = [
			'email' => 'required|email',
            'pass' => 'required|alpha_dash',
			'pass1' => 'required',
			'nick' => 'required',
			'qq' => 'required',
			
		];
		$messages = [
			'email.required' => '请输入电子邮箱！',
			'email.email' => '请输入正确的电子邮箱！',
			'pass.required' => '请输入帐号密码！',
			'pass.alpha_dash' => '请输入正确的密码格式',
			'pass1.required' => '请输入确认密码！',
			'nick.required' => '请输入昵称！',
			'qq.required' => '请输入腾讯QQ！',
		];

        $validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
		if($request->input('pass') != $request->input('pass1')){
			return redirect::back()->withInput()->withErrors('两次密码输入不一致，请重新输入！');
		}
		$users = User::where('user_email', $request->input('email'))->first();
		if($users){
			return redirect::back()->withInput()->withErrors('该帐号已被注册！');
		}
		$user = new User;
		$user->user_type	= 'member';
		$user->user_email	= $request->input('email');
		$user->user_pass	= md5($request->input('pass'));
		$user->nick_name	= $request->input('nick');
		$user->user_qq	= $request->input('qq');
		$user->user_status = '1';
		$user->save();
		return redirect('/login');
	}
}
