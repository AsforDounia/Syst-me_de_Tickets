<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category added successfully!');
    }


    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {


        $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',

        ]);

        $category = Category::findOrFail($request->id);

        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->back()->with('success', 'Catégorie modifiée avec succès!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée avec succès!');
    }
}
