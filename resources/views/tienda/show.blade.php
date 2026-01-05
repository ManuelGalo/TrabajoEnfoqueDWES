<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Columna Imagen --}}
                    <div>
                        <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow">
                    </div>

                    {{-- Columna Detalles --}}
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-2xl text-indigo-600 mt-2">{{ $product->price }} €</p>

                        {{-- LÓGICA DE STOCK TOTAL --}}
                        @php $stockTotal = $product->sizes->sum('stock'); @endphp

                        <div class="my-6">
                            @if($stockTotal <= 0)
                                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                                    <p class="text-red-700 font-bold">⚠️ Producto Agotado</p>
                                </div>
                            @elseif($stockTotal < 3)
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 animate-pulse">
                                    <p class="text-orange-700 font-bold">🔥 ¡Últimas unidades!</p>
                                    <p class="text-orange-600 text-sm">Solo quedan {{ $stockTotal }} pares disponibles.</p>
                                </div>
                            @else
                                <p class="text-green-600 font-medium flex items-center">
                                    <span class="h-2 w-2 bg-green-500 rounded-full mr-2"></span> Stock disponible
                                </p>
                            @endif
                        </div>

                        @if($stockTotal > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="mt-4">
                                    <label for="size" class="block text-sm font-medium text-gray-700">Selecciona tu talla:</label>
                                    <select name="size" id="size" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Elegir talla...</option>
                                        @foreach($product->sizes as $sizeItem)
                                            @if($sizeItem->stock > 0)
                                                <option value="{{ $sizeItem->size }}">
                                                    Talla {{ $sizeItem->size }} 
                                                    @if($sizeItem->stock < 3) (¡Solo {{ $sizeItem->stock }}!) @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="mt-6 w-full bg-indigo-600 text-white px-6 py-3 rounded-md font-bold hover:bg-indigo-700 transition">
                                    Añadir al carrito
                                </button>
                            </form>
                        @endif

                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-sm font-medium text-gray-900">Descripción</h3>
                            <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
