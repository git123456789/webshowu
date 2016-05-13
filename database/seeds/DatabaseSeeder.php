<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(Users::class);
		$this->call(Articles::class);
		$this->call(Categories::class);
		$this->call(Links::class);
		$this->call(Pages::class);
        $this->call(Websites::class);
        $this->call(Lables::class);
    }
}
