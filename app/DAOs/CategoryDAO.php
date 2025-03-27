<?php

namespace App\DAOs;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryDAO
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id): void
    {
        DB::table('categories')->where('id', $id)->delete();
    }
}