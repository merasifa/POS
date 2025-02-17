<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showCategories()
    {
        return view('product.categories'); 
    }

    public function showCategory($category)
    {
        return view('product.' . $category);  
    }
}
