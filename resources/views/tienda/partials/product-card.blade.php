<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition duration-300">
    {{-- Lógica de Stock Total --}}
    @php $stockTotal = $product->sizes->sum('stock'); @endphp

    {{-- Imagen del Producto --}}
    <div class="relative h-56 w-full">
        @if($product->images && count($product->images) > 0)
            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <img src="via.placeholder.com" class="w-full h-full object-cover">
        @endif
        
        {{-- Etiquetas de Stock --}}
        @if ($stockTotal < 3)
            <div class="absolute top-0 right-0 bg-orange-500 text-white px-3 py-1 m-2 rounded-lg text-sm font-bold animate-pulse">
                ¡Últimas unidades!
            </div>
            
        @elseif ($stockTotal <= 0)
            <div class="absolute top-0 right-0 bg-red-600 text-white px-3 py-1 m-2 rounded-lg text-sm font-bold">
                Agotado
            </div>
        @elseif($stockTotal >= 1)
            <div class="absolute top-0 right-0 bg-green-600 text-white px-3 py-1 m-2 rounded-lg text-sm font-bold">
                Disponible
            </div>
        @endif
    </div>

    {{-- Detalles --}}
    <div class="p-5">
        <a href="{{ route('tienda.show', $product->slug) }}">
            <h3 class="text-lg font-bold text-gray-800 truncate">{{ $product->name }}</h3>
            {{-- Badge de Género/Categoría --}}
            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-indigo-600 bg-indigo-200 last:mr-0 mr-1 mt-1">
                {{ $product->gender }}
            </span>
            <p class="text-gray-500 text-sm mt-2 h-10 overflow-hidden">{{ $product->description }}</p>
        </a>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-2xl font-extrabold text-indigo-600">{{ number_format($product->price, 2) }} €</span>
            <!-- <span class="text-sm {{ $stockTotal < 3 ? 'text-orange-600 font-bold' : 'text-gray-400' }}">
                Stock: {{ $stockTotal }}
            </span> -->
        </div>

        {{-- Botón de Acción --}}
        <div class="mt-5">
            @if($stockTotal > 0)
                {{-- IMPORTANTE: Enviamos a 'show' para que elija talla, no se puede añadir directo sin talla --}}
                <a href="{{ route('tienda.show', $product->slug) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    Ver opciones / tallas
                </a>
            @else
                <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                    No disponible
                </button>
            @endif
        </div>
    </div>
</div>
