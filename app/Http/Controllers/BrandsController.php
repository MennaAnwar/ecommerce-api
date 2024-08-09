<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    // retrieve all brands in database
    public function index(){
        $brands = Brands::paginate(10);

        return response()->json($brands,200);
    }

    // retrieve the brand by ID
    public function show($Id){
        $brand = Brands::find($Id);

        if($brand){
            return response()->json($brand,200);
        }

        return response()->json(["message" => "Brand not found"] , 404);
    }

    public function store(Request $request){
        try{
            $validated = $request->validate([
                'name' => 'required|unique:brands,name',
            ]);

            $brand = new Brands();
            $brand->name = $request->name;
            $brand->save();

            return response()->json(['message' => 'Brand added'],201);

        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }

    public function update($Id, Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|unique:brands,name',
            ]);

            $brand = Brands::where('id', $Id)->update(['name' => $request->name]);

            return response()->json(['message' => 'Brand updated'], 200);
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }

    public function delete($Id){
        try{
            $brand = Brands::find($Id);

            if($brand){
                $brand->delete();
                return response()->json(['message' => 'Brand deleted'], 200);
            } else{
                return response()->json(["message" => "Brand not found"] , 404);
            }
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }
}
