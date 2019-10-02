<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("property_types")->insert([
            ["name" => "Condo"],
            ["name" => 'House'],
            ["name" => 'Land']
        ]);
    }
}
