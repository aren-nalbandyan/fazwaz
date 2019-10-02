<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\PropertyController;


class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PropertyController::generateDummyData();
    }
}
