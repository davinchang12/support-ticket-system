<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request) {
        Category::create($request->validated());

        return redirect()->route('home.categories.index')->with('success', 'Successfully create new category.');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('home.categories.index')->with('success', 'Successfully edited category.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('home.categories.index')->with('success', 'Successfully deletedd category.');
    }
}
