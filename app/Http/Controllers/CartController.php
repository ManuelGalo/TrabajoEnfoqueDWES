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
        //Verificamos si la talla existe
        $request->validate([
            'size' => 'required|exist:product_size,size',
        ]);
        //Stock existente de la talla
        $sizeOption = $product->size()->where('size', $request->size)->first();
        $quantityToAdd = $request->quantity ?? 1;

        if (!$sizeOption || $sizeOption->stock <= 0){
            return redirect()->back()->with('error', 'Lo sentimos, esta talla esta agotada');
        }

        //obtener el carrito actual o array vacio si no existe
        $cart = session()->get('cart',[]);
        $cartIndex = $product->id . '-' . $request->size;
              
        //si el producto esta ya en el carrito y si la suma supera el stock.
        $currentQtyInCart = 0;
        if (isset($cart[$cartIndex])){
            $currentQtyInCart = $cart[$cartIndex]['quantity'];
        }
        //Control de stock
        if (($currentQtyInCart + $quantityToAdd) > $sizeOption->stock){
            return redirect()->back()->with('error', "No puedes añadir más unidades. Tenemos disponible: {$sizeOption->stock}");
        }    
        //si podemos añadir al carrito, se añade
        if (isset($cart[$cartIndex])){
            $cart[$cartIndex]['quantity'] += $quantityToAdd;
        }else{
            $cart[$cartIndex]=[
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "size" => $product->size,
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
