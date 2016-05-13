<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\RegList;
use DB, Log;

class caijiqi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caiji';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'zhu yao shi yong lai zuo ding shi cai ji';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $reg_list = DB::table('Reg_List')->where('reg_status','3')->get();
        foreach($reg_list as $str){
            dispatch(new RegList($str));
        }
        return true;
    }
}
