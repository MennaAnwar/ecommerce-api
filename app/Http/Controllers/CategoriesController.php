<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoriesController extends Controller
{
    // retrieve all brands in database
    public function index(){
        $categories = categories::paginate(10);

        return response()->json($categories,200);
    }

    // retrieve the brand by ID
    public function show($Id){
        $category = categories::find($Id);

        if($category){
            return response()->json($category,200);
        }

        return response()->json(["message" => "Category not found"] , 404);
    }

    public function store(Request $request){
        try{
            $validated = $request->validate([
                'name' => 'required|unique:categories,name',
                'image' => 'required'
            ]);

            $category = new categories();

            if($request->hasFile('image')){

                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('assets/uploads/category', $filename);
                $category->image = $filename;
                $category->name = $request->name;
                $category->save();
            }
            
            return response()->json(['message' => 'Category added'],201);

        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }

    public function update($Id, Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|unique:brands,name',
                'image' => 'required'
            ]);

            $category = categories::find($Id);
            if($request->hasFile('image')){
                $path = 'assets/uploads/category/' . $category->image;

                if (File::exists($path)) {
                    File::delete($path);
                }

                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('assets/uploads/category', $filename);
                $category->image = $filename;
                $category->name = $request->name;
                $category->update();
            }
            return response()->json(['message' => 'Category updated'], 200);
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }

    public function delete($Id){
        try{
            $category = categories::find($Id);

            if($category){
                $category->delete();
                return response()->json(['message' => 'Category deleted'], 200);
            } else{
                return response()->json(["message" => "Category not found"] , 404);
            }
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }
}
