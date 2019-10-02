<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //

    protected $fillable = ["title", "description", "bedroom", "bathroom", "property_type", "status_id", "for_sale", "for_rent", "project_id", "region_id"];

    const PAGINATION_COUNT = 20;

    protected $table = 'properties';

    public function region(){
        return $this->belongsTo("App\Region");
    }

    public function status(){
        return $this->belongsTo("App\Status");
    }

    public function propertyType(){
        return $this->belongsTo("App\PropertyType", "property_type", "id");
    }
}
