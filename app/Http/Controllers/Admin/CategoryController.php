<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::get();

        return Inertia::render('Admin/Category/Index',['categories'=>$categories]);
    }

    // store
    public function store(Request $request)
    {

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        // dd($category);
        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->update();
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    // delete
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
