<?php

namespace App\Http\Controllers;

use App\Models\Category;

class ChecklistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function category(Category $category)
    {
        return view('categories.subcategory', compact('category'));
    }
}
