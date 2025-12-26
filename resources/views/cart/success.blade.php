<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ¡Pedido Confirmado!
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de éxito -->
            <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-green-800">¡Gracias por tu compra!</h3>
                        <p class="mt-2 text-green-700">Tu pedido ha sido confirmado y está siendo procesado.</p>
                    </div>
                </div>
            </div>

            <!-- Detalles del pedido -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Resumen del Pedido</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Número de pedido</p>
                        <p class="text-lg font-bold text-blue-600">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if($order->status === 'pagado') bg-green-100 text-green-800
                            @elseif($order->status === 'pendiente') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Fecha</p>
                        <p class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} €</p>
                    </div>
                </div>

                @if($order->address)
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-1">Dirección de envío</p>
                        <p class="text-gray-800">{{ $order->address }}</p>
                    </div>
                @endif

                <!-- Productos del pedido -->
                <div class="border-t pt-4">
                    <h4 class="font-semibold mb-3">Productos:</h4>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between border-b pb-3">
                                <div class="flex items-center space-x-4">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             class="w-16 h-16 object-cover rounded"
                                             alt="{{ $item->product->name }}">
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">Talla: {{ $item->size }} | Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <p class="font-semibold">{{ number_format($item->price * $item->quantity, 2) }} €</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- Botones de acción -->
            <div class="flex gap-4">
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg text-center hover:bg-gray-700 font-semibold">
                    Volver a la tienda
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center hover:bg-blue-700 font-semibold">
                    Ver mis pedidos
                </a>
            </div>

            <!-- Número de seguimiento (simulado) -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Si tienes alguna duda, contacta con nosotros indicando el número de pedido <strong>#{{ $order->id }}</strong>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
