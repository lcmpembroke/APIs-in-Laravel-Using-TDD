<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Str;
use App\Product;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductCollection(Product::paginate());
    }

    public function store(Request $request) 
    {
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,                        
        ]);

        //return response()->json($product,201);
        // instead of returning the Eloquent model, we can say
        return response()->json(new ProductResource($product),201);
    }


    public function show(int $id) 
    {
        $product = Product::findOrFail($id);
        // don't give status code as it's 200 by default
        return response()->json(new ProductResource($product));
    }

    public function update(Request $request, int $id) 
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,            
            'price' => $request->price,            
        ]);

        return response()->json(new ProductResource($product));
    }    

    public function destroy(int $id) 
    {

        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null,204);     
    }    
}
