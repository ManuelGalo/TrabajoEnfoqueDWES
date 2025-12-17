<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition duration-300">
    {{-- Imagen del Producto --}}
    
    <div class="relative h-56 w-full">
        @if($product->images && count($product->images) > 0)
            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <img src="via.placeholder.com" class="w-full h-full object-cover">
        @endif
        
        @if($product->stock <= 0)
            <div class="absolute top-0 right-0 bg-red-600 text-white px-3 py-1 m-2 rounded-lg text-sm font-bold">
                Agotado
            </div>
        @endif
    </div>

    {{-- Detalles --}}
    <div class="p-5">
        <a href="{{ route('tienda.show', $product->slug) }}">
        <h3 class="text-lg font-bold text-gray-800 truncate">{{ $product->name }}</h3>
        <p class="text-gray-500 text-sm mt-1 h-10 overflow-hidden">{{ $product->description }}</p>
        </a>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-2xl font-extrabold text-indigo-600">{{ number_format($product->price, 2) }} €</span>
            <span class="text-sm text-gray-400">Stock: {{ $product->stock }}</span>
        </div>

        {{-- Botón Añadir al Carrito --}}
        <div class="mt-5">
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg xmlns="www.w3.org" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Añadir
                    </button>
                </form>
            @else
                <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                    No disponible
                </button>
            @endif
        </div>
    </div>
</div>
