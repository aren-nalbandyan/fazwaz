<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Status;
use App\Models\PropertyType;
use App\Models\Region;
use App\Models\Project;
use Faker\Factory;

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

    }/*end of function getProperties*/

    /**
     * Generates Dummy data to database
     *
     * @return void
     */
    public static function generateDummyData()
    {
        DB::disableQueryLog();
        $faker = Factory::create();
        $countries = Country::all();
        $regions = Region::all();
        $fourthRegion = self::getRegion4Id($regions, $countries[0]->id);
        $status = Status::all();
        $activeStatusId = self::getActiveStatusId($status);
        $inactiveStatusId = self::getInactiveStatusId($regions);
        $propertyTypes = PropertyType::all();
        $projects = [];
        for($i = 0; $i < 10000; $i++){
            $projects[] = ["name" => $faker->name];
        }
        DB::table("projects")->insert($projects);
        $projects = Project::all();
        $projectsCount = count($projects);
        $condoPropertyTypeId = self::getPropertyTypeCondoId($propertyTypes);
        $housePropertyTypeId = self::getPropertyTypeHouseId($propertyTypes);
        $sale_bedrooms_count = 0;
        for($i = 0; $i < 1000; $i++)
        {
            $newProperties = [];
            for ($k = 0; $k < 100; $k ++)
            {
                $forSale = $faker->numberBetween(0, 1);
                $forRent = $faker->numberBetween(0, 1);
                $randomRegion = $faker->numberBetween(0, count($regions) - 1);
                $randomPropertyType = $faker->numberBetween(0, count($propertyTypes) - 1);
                $statusRand = $faker->numberBetween(0, count($status) - 1);
                $bedroom = $faker->numberBetween(1, 10);
                if($bedroom === 2 &&
                    $status[$statusRand]["name"] === "Active" &&
                    $forSale &&
                    $propertyTypes[$randomPropertyType]["name"] === "Condo"){
                    if($sale_bedrooms_count >= 3000){
                        do{
                            $bedroom = $faker->numberBetween(1, 10);
                            $statusRand = $faker->numberBetween(1, count($status));
                            $forSale = $faker->numberBetween(0, 1);
                            $randomPropertyType = $faker->numberBetween(0, count($propertyTypes) - 1);
                        } while ($bedroom === 2 &&
                                 $status[$statusRand]["name"] === "Active" &&
                                 $forSale &&
                                 $propertyTypes[$randomPropertyType]["name"] === "Condo");
                    }
                    else $sale_bedrooms_count ++;
                }
                if($regions[$randomRegion]->id === $fourthRegion &&
                    $status[$statusRand]["name"] === "Inactive" &&
                    $propertyTypes[$randomPropertyType]["name"] === "House" &&
                    $forRent) {
                    do{
                        $forRent = $faker->numberBetween(0, 1);
                        $randomRegion = $faker->numberBetween(0, count($regions) - 1);
                        $randomPropertyType = $faker->numberBetween(0, count($propertyTypes) - 1);
                        $statusRand = $faker->numberBetween(0, count($status) - 1);

                    } while($regions[$randomRegion]->id === $fourthRegion &&
                            $status[$statusRand]["name"] === "Inactive" &&
                            $propertyTypes[$randomPropertyType]["name"] === "House" &&
                            $forRent);
                }
                $randProject = $projects[rand(0, $projectsCount - 1)];
                $newProperties[] = [
                    "title" =>  $faker->name,
                    "description" => $faker->text(50),
                    "bedroom" => $bedroom,
                    "bathroom" => $faker->numberBetween(0, 10),
                    "status_id" => $status[$statusRand]->id,
                    "for_sale" => $forSale,
                    "for_rent" => $forRent,
                    "region_id" => $regions[$randomRegion]->id,
                    "property_type" => $propertyTypes[$randomPropertyType]->id,
                    "project_id" => $randProject->id,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
            }
            DB::table("properties")->insert($newProperties);
        }
        $two_bedrooms_count = Property::where('bedroom', 2)
            ->where("status_id", $activeStatusId)
            ->where("for_sale", 1)
            ->where("property_type", $condoPropertyTypeId)->count();
        if($two_bedrooms_count < 3000) {
            $limit = 3000 - $two_bedrooms_count;
            $not_two_bedrooms = Property::where('bedroom', '<>', 2)
                ->orWhere("status_id", "<>", $activeStatusId)
                ->orWhere("for_sale", 0)->where("property_type", "<>", $condoPropertyTypeId)
                ->limit($limit)->get();

            foreach ($not_two_bedrooms as $item)
            {
                $item->bedroom = 2;
                $item->status_id = $activeStatusId;
                $item->save();
            }
        }
        $randProject = $randProject = $projects[rand(0, $projectsCount - 1)];
        $propertyProjectCount = Property::where("project_id", $randProject->id)
            ->where("status_id", $inactiveStatusId)
            ->where("property_type", $housePropertyTypeId)
            ->where("region_id", $fourthRegion)
            ->count();
        if($propertyProjectCount < 2001){
            $properties = Property::where('bedroom', '<>', 2)
                ->orWhere("project_id", "<>", $randProject->id)
                ->orWhere('region_id', $fourthRegion)
                ->orWhere("status_id", '<>', $inactiveStatusId)
                ->orWhere("property_type", '<>', $housePropertyTypeId)
                ->limit(2001 - $propertyProjectCount)
                ->get();
            foreach ($properties as $property){
                $property->project_id = $randProject->id;
                $property->save();
            }
        }
    }/*end of function generateDummyData*/

    /**
     * @param array [id => integer, name => string, created_at => datetime|null, updated_at => datetime|null] $statuses
     * @return integer
     */
    public static function getActiveStatusId( $statuses )
    {
        foreach ( $statuses as $number => $status ) {
            if( $status->name === "Active" ){
                return $status->id;
            }
        }
    }

    /**
     * @param array [id => integer, name => string, created_at => datetime|null, updated_at => datetime|null] $propertyTypes
     * @return string
     */
    public static function getPropertyTypeCondoId( $propertyTypes )
    {
        foreach ($propertyTypes as $propertyType){
            if($propertyType->name === "Condo"){
                return $propertyType->name;
            }
        }
    }

    /**
     * @param array [id => integer, name => string, created_at => datetime|null, updated_at => datetime|null][] $propertyTypes
     * @return string
     */
    public static function getPropertyTypeHouseId( $propertyTypes )
    {
        foreach ( $propertyTypes as $propertyType )
        {
            if( $propertyType->name === "House" ){
                return $propertyType->name;
            }
        }
    }

    /**
     * @param array [id => integer, name => string, country => obj, created_at => datetime|null, updated_at => datetime|null] $regions
     * @param integer $countryId
     * @return integer
     */
    public static function getRegion4Id($regions, $countryId)
    {
        foreach ( $regions as $region )
        {
            if( $region->name === "Region 4" && $region->country->id === $countryId )
            {
                return $region->id;
            }
        }
    }

    /**
     * @param array [id => integer, name => string, created_at => datetime|null, updated_at => datetime|null] $statuses
     * @return integer
     */
    public static function getInactiveStatusId( $statuses )
    {
        foreach ( $statuses as $number => $status )
        {
            if( $status->name === "Inactive" )
            {
                return $status->id;
            }
        }
    }
}
