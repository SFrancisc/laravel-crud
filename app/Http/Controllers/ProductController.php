<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);

        return view('products.index',compact('products'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate input
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg',
            'detail' => 'required'
        ]);

                //create a new product
            $product=Product::create($request -> all());

        if($request->has('image')){

            $product->image = $request->file('image')->storePublicly('products');
            $product->save();
        }


        //redirect to user and send friendly message
        return redirect()->route('products.index')->with('succes','Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //validate input
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg',
            'detail' => 'required'
        ]);

        //create a new product
        $product->update(['name' => $request->name, 'detail' => $request->detail, ]);

        if($request->has('image')){
            if(Storage::exists($product->image)){
                Storage::delete($product->image);
            }

            $product->image = $request->file('image')->storePublicly('products');
            $product->save();
        }


        //redirect to user and send friendly message
        return redirect()->route('products.index')->with('succes','Product created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if(Storage::exists($product->image)){
            Storage::delete($product->image);
        }
        //delete the product
        $product->delete();

        //redirect the user&message
        return redirect()->route('products.index')->with('succes','Product deleted successfully');
    }
}
