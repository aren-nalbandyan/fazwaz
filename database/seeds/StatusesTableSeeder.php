<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("statuses")->insert([
            ["name" => "Active"],
            ["name" => "Inactive"],
            ["name" => "Daft"],
        ]);
    }
}
