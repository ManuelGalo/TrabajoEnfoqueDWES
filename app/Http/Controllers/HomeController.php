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

    /* public function category($category)
    {   
        $products = Product::where('category', $category)
                    ->where('is_active', true)
                    ->paginate(15); 

        return view('tienda.category', compact('products', 'category'));
    } */

    public function category(Request $request, $category)
    {
        $query = Product::where('category', $category)->where('is_active', true);

        // Filtro por género
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filtro por precio minimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        //Filtro por precio máximo
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price); 
        }

        // Filtro por talla (Relación)
        if ($request->filled('size')) {
            $query->whereHas('sizes', function($q) use ($request) {
                $q->where('size', $request->size);
            });
        }

        $products = $query->paginate(15)->withQueryString();

        return view('tienda.category', compact('products', 'category'));
        
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail(); 

        return view('tienda.show', compact('product'));
    }

}
