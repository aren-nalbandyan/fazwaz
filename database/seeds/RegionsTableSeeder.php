<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $thailandId = Country::where("name", "Thailand")->first()->id;
        $cambodiaId = Country::where("name", "Cambodia")->first()->id;
        for($i = 1; $i < 7; $i++){
            DB::table("regions")->insert([
                "name" => "Region $i", "country_id" => $thailandId
            ]);
            DB::table("regions")->insert([
                "name" => "Region $i", "country_id" => $cambodiaId
            ]);
        }
    }
}
