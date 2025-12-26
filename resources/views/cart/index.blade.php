<x-app-layout>
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Tu Carrito</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4">Producto</th>
                        <th class="p-4">Talla</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4">Cantidad</th>
                        <th class="p-4">Subtotal</th>
                        <th class="p-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('cart') as $id => $details)
                        <tr class="border-b">
                            <td class="p-4 flex items-center">
                                @if(isset($details['image']))
                                    <img src="{{ asset('storage/' . $details['image']) }}" 
                                         class="w-12 h-12 object-cover rounded mr-4"
                                         alt="{{ $details['name'] }}">
                                @endif
                                {{ $details['name'] }}
                            </td>
                            <td class="p-4">{{ $details['size'] }}</td>
                            <td class="p-4">{{ number_format($details['price'], 2) }} €</td>
                            <td class="p-4">{{ $details['quantity'] }}</td>
                            <td class="p-4">{{ number_format($details['price'] * $details['quantity'], 2) }} €</td>
                            <td class="p-4">
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <div class="text-2xl font-bold">
                Total: {{ number_format($total, 2) }} €
            </div>
            <div class="flex gap-2">
                <a href="{{ route('home') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                    Seguir Comprando
                </a>
                @auth
                    <a href="{{ route('cart.checkout') }}" 
                       class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700">
                        Proceder al Pago
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Inicia sesión para comprar
                    </a>
                @endauth
            </div>
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-xl text-gray-600">El carrito está vacío.</p>
            <a href="{{ route('home') }}" 
               class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Ir a la tienda
            </a>
        </div>
    @endif
</div>
</x-app-layout>
