<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('tienda.index', [
            'deporte' => Product::where('category', 'deporte')->where('is_active', true)->get(),
            'casual' => Product::where('category', 'casual')->where('is_active', true)->get(),
            'botas' => Product::where('category', 'botas')->where('is_active', true)->get(),
        ]);
    }

    public function category($category)
    {   
        $products = \App\Models\Product::where('category', $category)
                    ->where('is_active', true)
                    ->paginate(15); 

        return view('tienda.category', compact('products', 'category'));
    }
    public function show($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail(); 

        return view('tienda.show', compact('product'));
    }

}
