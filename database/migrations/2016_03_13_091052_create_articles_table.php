<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            //网站文章数据库
			$table->increments('art_id');
			$table->integer('user_id')->unsigned();
			$table->smallInteger('cate_id')->unsigned();
			$table->string('art_title','100');
			$table->string('art_tags','50');
			$table->string('copy_from','50');
			$table->string('copy_url','200');
			$table->string('art_intro','200');
			$table->text('art_content');
			$table->integer('art_views')->unsigned();
			$table->tinyInteger('art_ispay')->unsigned();
			$table->tinyInteger('art_istop')->unsigned();
			$table->tinyInteger('art_isbest')->unsigned();
			$table->tinyInteger('art_status')->unsigned();
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
        Schema::drop('articles');;
    }
}
