<?php
namespace App\Repositories\Category;

use App\Models\Category;

Class CategoryRepository {
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAllCategory(){
        $categories = $this->category->all();
        return $categories;
    }
}
