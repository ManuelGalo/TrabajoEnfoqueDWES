<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirmar Pedido
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Resumen del Pedido</h3>

                <!-- Productos -->
                <div class="space-y-4 mb-6">
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between border-b pb-4">
                            <div class="flex items-center space-x-4">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         class="w-20 h-20 object-cover rounded">
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">Talla: {{ $item['size'] }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            <p class="font-semibold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</p>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between text-xl font-bold">
                        <span>TOTAL:</span>
                        <span>{{ number_format($total, 2) }} €</span>
                    </div>
                </div>

                <!-- Dirección de envío -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de envío</label>
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <textarea name="address" rows="3" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Calle, número, ciudad, código postal..."></textarea>
                        
                        <div class="mt-4 space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="delivery" value="envio" checked class="mr-2">
                                <span>Envío a domicilio (Gratis)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="delivery" value="tienda" class="mr-2">
                                <span>Recoger en tienda</span>
                            </label>
                        </div>

                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('cart.index') }}" 
                               class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg text-center hover:bg-gray-400">
                                Volver al carrito
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                                Proceder al pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
