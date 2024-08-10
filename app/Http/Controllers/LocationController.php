<?php

namespace App\Http\Controllers;

use App\Models\location;
use Exception;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "street" => "required",
            "building" => "required",
            "area" => "required"
        ]);

        location::create([
            "street" => $request->street,
            "building" => $request->building,
            "area" => $request->area,
            "user_id" => $request->user_id,
        ]);

        return response()->json(['message'=>'Location added'],201);
    }

    public function update($Id, Request $request){
        $request->validate([
            "street" => "required",
            "building" => "required",
            "area" => "required"
        ]);

        $location = location::find($Id);

        if($location){
            $location->street = $request->street;
            $location->building = $request->building;
            $location->area = $request->area;

            $location->update();

            return response()->json(['message'=>'Location updated'],201);
        } else{
            return response()->json(['message'=>'Location not found'],404);
        }
    }


    public function delete($Id){
        try{
            $location = location::find($Id);

            if($location){
                $location->delete();
                return response()->json(['message' => 'Location deleted'], 200);
            } else{
                return response()->json(["message" => "Location not found"] , 404);
            }
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }
}
