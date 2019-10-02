<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //

    public static function getCountryIdByName($name){
        return self::where("name", $name)->first()->id;
    }

    public function regions(){
        return $this->hasMany("App\Region");
    }

//    public function building(){
//        return $this->belongsTo("App\Building");
//    }
}
