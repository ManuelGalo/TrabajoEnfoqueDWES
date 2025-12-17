<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            {{-- Galería de Imágenes --}}
            <div>
                <div class="grid grid-cols-1 gap-4">
                    @if($product->images && count($product->images) > 0)
                        {{-- Imagen Principal --}}
                        <img src="{{ asset('storage/' . $product->images[0]) }}" class="w-full rounded-lg shadow-lg">
                        
                        {{-- Resto de la galería --}}
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $index => $image)
                                <img src="{{ asset('storage/' . $image) }}" class="h-24 w-full object-cover rounded-md cursor-pointer hover:opacity-75 transition">
                            @endforeach
                        </div>
                    @else
                        <img src="via.placeholder.com" class="w-full rounded-lg">
                    @endif
                </div>
            </div>

            {{-- Información --}}
            <div class="flex flex-col">
                <nav class="mb-4 text-sm text-gray-500">
                    <a href="/" class="hover:text-indigo-600">Tienda</a> / 
                    <a href="{{ route('tienda.category', $product->category) }}" class="capitalize hover:text-indigo-600">{{ $product->category }}</a>
                </nav>

                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                <p class="text-3xl text-indigo-600 font-bold mb-6">{{ number_format($product->price, 2) }} €</p>
                
                <div class="prose prose-indigo mb-8">
                    <h3 class="text-lg font-bold">Descripción</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>

                <div class="mt-auto p-6 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500">Disponibilidad:</span>
                        <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                            {{ $product->stock > 0 ? $product->stock . ' unidades en stock' : 'Agotado' }}
                        </span>
                    </div>

                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-lg border-gray-300">
                                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-indigo-700 transition">
                                    Añadir al Carrito
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
