<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoriesStoreRequest;
use App\Http\Requests\AdminCategoriesUpdateRequest;


class CategoriesController extends Controller
{
    public function index()
    {
        $category = Category::all();

        return view("admin.categories.index", ["categories" => $category]);
    }

    public function create()
    {
        return view("admin.categories.create");
    }

    public function store(AdminCategoriesStoreRequest $request)
    {
        Category::create($request->all());
        return redirect()->route("categories.index");
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view("admin.categories.edit", ["category" => $category]);
    }

    public function update(AdminCategoriesUpdateRequest $request, $id)
    {
        $category = Category::find($id);

        $category->update($request->all());

        return redirect()->route("categories.index");
    }

    public function destroy($id)
    {
        Category::find($id)->delete();

        return redirect()->route("categories.index");
    }
}
