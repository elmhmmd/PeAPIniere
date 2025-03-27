<?php

namespace App\Http\Controllers;

use App\DAOs\CategoryDAO;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryDAO;

    public function __construct(CategoryDAO $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required|string|unique:categories']);
        $category = $this->categoryDAO->create($request->only('category_name'));
        return response()->json($category, 201);
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['category_name' => 'required|string|unique:categories,category_name,' . $id]);
        $category = $this->categoryDAO->update($id, $request->only('category_name'));
        return response()->json($category);
    }

    public function destroy(int $id)
    {
        $this->categoryDAO->delete($id);
        return response()->json(['message' => 'Category deleted']);
    }
}