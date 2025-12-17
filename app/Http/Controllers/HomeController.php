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
}
