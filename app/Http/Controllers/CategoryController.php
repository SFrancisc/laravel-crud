<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(5);

        return view('categories.index',compact('categories'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg',
            'detail' => 'required'
        ]);

                //create a new product
            $category=Category::create($request -> all());

        if($request->has('image')){

            $category->image = $request->file('image')->storePublicly('categories');
            $category->save();
        }


        //redirect to user and send friendly message
        return redirect()->route('categories.index')->with('succes','Categories created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg',
            'detail' => 'required'
        ]);

        //create a new product
        $category->update(['name' => $request->name, 'detail' => $request->detail, ]);

        if($request->has('image')){
            if(Storage::exists($category->image)){
                Storage::delete($category->image);
            }

            $category->image = $request->file('image')->storePublicly('categories');
            $category->save();
        }


        //redirect to user and send friendly message
        return redirect()->route('categories.index')->with('succes','Category created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if(Storage::exists($category->image)){
            Storage::delete($category->image);
        }
        //delete the product
        $category->delete();

        //redirect the user&message
        return redirect()->route('categories.index')->with('succes','Categories deleted successfully');
    }
}