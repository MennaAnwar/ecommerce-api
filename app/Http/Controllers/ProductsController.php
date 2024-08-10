<?php

namespace App\Http\Controllers;

use App\Models\products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    public function index() {
    $products = products::paginate(10);
    if ($products->isEmpty()) {
        return response()->json("No products");
    }
    return response()->json($products);
    }

    public function show($id) {
        $product = products::find($id);
        if (!$product) {
            return response()->json("Product was not found");
        }
    }

    public function store(Request $request) {
    $request->validate([
        "name" => "required",
        "category_id" => "required",
        "price" => "required|numeric",
        "stock" => "required|integer",
        "image" => "nullable|image",
    ]);

    $product = new products();
    $product->name = $request->input('name');
    $product->category_id = $request->input('category_id');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');

    if ($request->hasFile('image')) {
        $path = 'uploads/products/';
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        try {
            $file->move($path, $filename);
        } catch (\Exception $e) {
            // Handle exception
        }
        $product->image = $filename;
    }

    $product->save();
    return response()->json("Product added", 201);
    }

    public function update($id, Request $request) {
    $request->validate([
        "name" => "required",
        "category_id" => "required",
        "price" => "required|numeric",
        "stock" => "required|integer",
        "image" => "nullable|image",
    ]);

    $product = products::find($id);
    $oldImage = $product->image;

    if ($request->hasFile('image')) {
        $path = 'uploads/products/';
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        try {
            $file->move($path, $filename);
        } catch (\Exception $e) {
            // Handle exception
        }
        $product->image = $filename;

        // Optionally delete the old image
        if ($oldImage) {
            File::delete($path . $oldImage);
        }
    }

    $product->save();
    return response()->json("Product updated",201);
    }

    public function delete($Id){
        try{
            $product = products::find($Id);

            if($product){
                $product->delete();
                return response()->json(['message' => 'Product deleted'], 200);
            } else{
                return response()->json(["message" => "Product not found"] , 404);
            }
        } catch(Exception $e){
            return response()->json(['message' => $e],400);
        }
    }
}
