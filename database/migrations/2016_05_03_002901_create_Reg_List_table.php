<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reg_List', function (Blueprint $table) {
            $table->increments('reg_id');
            $table->string('reg_url','255'); //网站域名
            $table->string('reg_host','255'); //网站域名
            $table->string('reg_list','255'); //网站采集列表规则
            $table->tinyInteger('reg_ishost')->unsigned(); //是否开启url对接
            $table->smallInteger('cate_id')->unsigned(); //分类关联ID字段
            $table->text('reg_content'); //上一次采集的内容
            $table->tinyInteger('reg_status')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Reg_List');
    }
}
