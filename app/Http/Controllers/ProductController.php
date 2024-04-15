<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
            'detail' => 'required'
        ]);
        //create a new product
        Product::create($request -> all());
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
            'detail' => 'required'
        ]);
        //create a new product
        $product->update($request -> all());
        //redirect to user and send friendly message
        return redirect()->route('products.index')->with('succes','Product created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //delete the product
        $product->delete();

        //redirect the user&message
        return redirect()->route('products.index')->with('succes','Product deleted successfully');
    }
}
