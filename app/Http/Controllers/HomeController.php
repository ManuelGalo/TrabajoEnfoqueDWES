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
                $q->where('size', $request->size)
                ->where('stock', '>', 0); // <--- IMPORTANTE: Solo tallas que se puedan comprar
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
        $cart = session()->get('cart', []);

        // Modificamos las tallas dinámicamente para la vista
        $product->sizes->map(function ($sizeOption) use ($cart, $product) {
            $cartKey = $product->id . '-' . $sizeOption->size;
            $inCart = $cart[$cartKey]['quantity'] ?? 0;
            
            // Stock real disponible para este usuario específico
            $sizeOption->available_stock = max(0, $sizeOption->stock - $inCart);
            return $sizeOption;
        }); 

        return view('tienda.show', compact('product'));
    }

}
