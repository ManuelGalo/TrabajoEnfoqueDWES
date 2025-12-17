<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    
    //Añadir al carrito

    public function add (Request $request, Product $product)
    {   
        //Verificamos si el producto tine stock
        if ($product->stock <= 0){
            return redirect()->back()->with('error', 'Lo sentimos, este artículo esta agotado');
        }
        //obtener el carrito actual o array vacio si no existe
        $cart = session()->get('cart',[]);
        $quantityToAdd = $request->quantity ?? 1;
      
        //si el producto esta ya en el carrito y si la suma supera el stock.
        $currentQtyInCart =  isset($cart[$product->id]) ?  $cart[$product->id]['quantity'] : 0;
        if (($currentQtyInCart + $quantityToAdd) > $product->stock){
            return redirect()->back()->with('error', "No puedes añadir más unidades. Tenemos disponible: {$product->stock}");
        }    
        //si podemos añadir al carrito, se añade
        if (isset($cart[$product->id])){
            $cart[$product->id]['quantity'] += $quantityToAdd;
        }else{
            $cart[$product->id]=[
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->images[0] ?? null
            ];
        }

        //Guarda en la sesion
        session()->put('cart', $cart);
        return redirect()->back()->with('info', '¡Zapatillas añadidas!');
    }
    
    //ver carrito
    public function index(){
        $cart = session()->get('cart',[]);
        $total = 0;
        foreach($cart as $item){
            $total += $item['price']*$item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }
 
    //Eliminar del carrito

    public function remove($id)
    {
       $cart = session()->get('cart', []);
       if(isset($cart[$id])){
        unset($cart[$id]);
        session()->put('cart', $cart);
       }
        return redirect()->back()->with('info', 'Zapatillas eliminadas');
    }
}
