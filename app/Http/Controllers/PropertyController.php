<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Status;
use App\Models\PropertyType;

class PropertyController extends Controller
{
    //

    public function index( Request $request )
    {
        $properties = $this->getProperties($request);
        $statuses = Status::get();
        $countries = Country::with("regions")->get();
        $types = PropertyType::all();
        return view("welcome", [ 'statuses' => $statuses, 'countries' => $countries, 'types' => $types, "properties" => $properties]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getProperties( Request $request )
    {
        $buildings = Property::query();

        //if searching by title
        if($request->input("search_title")){
            $buildings->where("title",'LIKE', '%' . $request->input("search_title") . "%");
        }

        //if searching by description
        if($request->input("search_description")){
            $buildings->where("description",'LIKE', '%' . $request->input("search_description") . "%");
        }

        //if searching by type
        if($request->input("search_type")){
            $buildings->where("property_type",'=',  $request->input("search_type") );
        }

        //if filtering by bathroom number
        if($request->input("search_bathroom")){
            $buildings->where("bathroom",'=',  $request->input("search_bathroom") );
        }

        //if filtering by bedroom number
        if($request->input("search_bedroom")){
            $buildings->where("bedroom",'=',  $request->input("search_bedroom") );
        }

        //if filtering only for rent properties
        if($request->input("search_rent") != ''){
            $buildings->where("for_rent",'=',  $request->input("search_rent") );
        }

        //if filtering only for sale properties
        if($request->input("search_sale") != ''){
            $buildings->where("for_sale",'=',  $request->input("search_sale") );
        }

        //if filtering by status
        if($request->input("status_id")){
            $buildings->where("status_id", $request->input("status_id"));
        }

        //if filtering by region
        if($request->input("region_id")){
            $buildings->where("region_id", $request->input("region_id"));
        }

        //if ordering by any column
        if($request->input("orderByType") && $request->input("fieldName")){
            $buildings->orderBy($request->input("fieldName"), $request->input("orderByType"));
        }

        $buildings->with(["region", "status", "propertyType"]);
        $count = $buildings->count();

        $paginate = Property::PAGINATION_COUNT;
        //count of pages
        $count = ceil($count / $paginate);
        $page = $request->input("page",1);

        $offSet = ($page * $paginate) - $paginate;

        $data = $buildings->skip($offSet)->take($paginate)->get();

        return json_encode([ 'data' => $data, 'total' => $count ]);

    }
}
