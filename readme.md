# 秀站分类目录

## 简介

一个基于laravel5.2的分类目录、导航程序！这里只是简单的完善了，后续还有很多强大的功能正在完善中……

秀文档还在积极的完善中……请大家耐心等待……

是lambq一个独立开发程序、在lambq学习laravel5.2的时候有感而来……

## 有哪些模块？
* 资讯文章管理模块
* 目录网站管理模块
* 吊炸天的秀妹机器人，基于反向索引上一个外链接、自动收录。缩略图、数据采集、异步列队处理数据方式等

## 有哪些功能？
* 定时运行程序功能（cron任务调度）
* 长耗时运行程序功能（queue队列）
* phpquery采集器功能（语法类似jquery的dom采集操作——是我见过最不错的）
* curl动态代理（是专门针对防止采集的）
* 基于http反向索引外链（自动收录网站）

## 有那些模块和功能的组合呢？
* 秀妹组合：基于http反向索引外链、自动收录网站并生成网站缩略图和各种数据的收集。这是一个长耗时php运行时间有可能超出60秒、所以我建立了一个队列(jobs)专门处理“秀妹抛过来的任务”。
* 文章采集组合：建立一个采集规则表、调用cron任务调度定时每一分钟读取规则表里面的所以规则、循环抛给处理规则的队列(jobs)、然后把所有要采集的文章(标题和链接)抛给文章采集队列(jobs)。

## 使用了laravel扩展包？
* jenssegers/agent——轻松识别客户端信息
* stevenyangecho/laravel-u-editor——百度编辑器
* overtrue/laravel-lang——laravel多个国家的语言包
* predis/predis——最好的redis-php扩展包

## 使用了laravel哪些服务？
* artisan工具(Artisan Console)
* 缓存(Cache)
* 文件系统/云存储(Filesystem / Cloud Storage)
* 辅助函数(Helpers)
* 分页(Pagination)
* 队列(Queue)
* session
* 任务调度(Task Scheduling)

## 使用了php哪些扩展？
* fileinfo
* openssl
* pdo
* mbstring
* tokenizer
* pcntl
* redis
* memcached

## 额外使用了哪些程序呢？
* python的进程管理控制系统(supervisor)
* linux的定时任务系统“只能精确的分钟”(cron/crontab)

## 感激
感谢以下的项目,排名不分先后

* [phphub](https://phphub.org)
* [bootstrap](http://www.bootcss.com)
* [laravel](http://www.leravel.com)
* [秀站分类目录](http://www.webshowu.com)

## 有bug反馈
在使用中有任何问题，欢迎反馈给我，可以用以下联系方式跟我交流

* qq群：57176386
