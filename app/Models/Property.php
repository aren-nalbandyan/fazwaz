<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["title", "description", "bedroom", "bathroom", "property_type", "status_id", "for_sale", "for_rent", "project_id", "region_id"];

    /**
     * The count of properties on each page
     *
     * @var integer
     */
    const PAGINATION_COUNT = 20;

    public function region(){
        return $this->belongsTo("App\Models\Region");
    }

    public function status(){
        return $this->belongsTo("App\Models\Status");
    }

    public function propertyType(){
        return $this->belongsTo("App\Models\PropertyType", "property_type", "id");
    }
}
