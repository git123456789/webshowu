<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		//创建用户数据表
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id'); //用户自定义
			$table->enum('user_type', array('admin', 'member','recruit','vip'));//用户类型 admin=管理员 member=注册会员 recruit=快速收录 vip=VIP会员
            $table->string('user_email','50'); //用户邮箱
            $table->string('user_pass', '32'); //用户密码
			$table->string('open_id','32'); //用户的快捷登陆的open编号 例如QQ登陆、微博登陆等等
			$table->string('nick_name','20'); //用户呢称
			$table->string('user_qq','20'); //用户QQ号码
			$table->smallInteger('user_score')->unsigned();
			$table->string('verify_code','32'); //用户快捷登陆验证戳
			$table->tinyInteger('user_status')->unsigned(); //用户的状态 0=待验证 1=已验证
			$table->integer('login_ip')->unsigned(); //用户登陆IP地址
			$table->smallInteger('login_count')->unsigned(); //用户登陆次数
            $table->rememberToken(); 
            $table->timestamps(); //这是laravel特有的功能、会给这个用户数据表添加2个时间段 一个就是表的数据第一次创建时间和最后一次修改时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
