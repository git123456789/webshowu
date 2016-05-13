<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Websites;
use phpQuery, Storage, Log;

class WebThum extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        //
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $filename = 'xiumei/'.date('Ymd',time()).'/'.$this->url.'.jpg';
        $url = 'http://api.webthumbnail.org/?width=480&height=330&screen=1280&url='.$this->url;
        $ch =curl_init(); 
        $timeout =60; 
        curl_setopt($ch,CURLOPT_URL,$url); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
        $img=curl_exec($ch); 
        curl_close($ch);
        Storage::put($filename, $img);
        Log::info('没有接收到网站域名');
    }
}
