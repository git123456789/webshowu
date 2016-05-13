<?php

use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$dir_users = DB::connection('webshowu')->select('select * from users');
		
		foreach($dir_users as $str){
			DB::table('users')->insert([
				'user_id' => $str->user_id ,
				'user_type' => $str->user_type,
				'user_email' => $str->user_email,
				'user_pass' => $str->user_pass,
				'open_id' => $str->open_id,
				'nick_name' => $str->nick_name,
				'user_qq' => $str->user_qq,
				'user_score' => $str->user_score,
				'verify_code' => $str->verify_code,
				'user_status' => $str->user_status,
				'login_ip' => $str->login_ip,
				'login_count' => $str->login_count,
				'remember_token' => '',
				'created_at' => $str->created_at,
				'updated_at' => $str->updated_at,
			]);
		}
    }
}
