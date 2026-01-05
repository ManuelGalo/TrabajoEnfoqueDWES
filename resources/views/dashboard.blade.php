<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Panel
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    ¡Bienvenido, {{ Auth::user()->name }}!
                </h3>
                <p class="text-gray-600">Gestiona tus pedidos y perfil desde aquí.</p>
            </div>

            <!-- Cards de acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Ver productos -->
                <a href="{{ route('home') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ver Productos</h3>
                    <p class="text-sm text-gray-600">Explora nuestro catálogo completo</p>
                </a>

                <!-- Ver carrito -->
                <a href="{{ route('cart.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="ml-auto bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Mi Carrito</h3>
                    <p class="text-sm text-gray-600">
                        @if(session('cart') && count(session('cart')) > 0)
                            {{ count(session('cart')) }} productos en el carrito
                        @else
                            Tu carrito está vacío
                        @endif
                    </p>
                </a>

                <!-- Mi perfil -->
                <a href="{{ route('profile.edit') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Mi Perfil</h3>
                    <p class="text-sm text-gray-600">Edita tu información personal</p>
                </a>
            </div>

            <!-- Mis pedidos recientes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Mis Pedidos Recientes</h3>
                
                @php
                    $recentOrders = Auth::user()->orders()->latest()->take(5)->get();
                @endphp

                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 font-semibold text-green-600">{{ number_format($order->total_amount, 2) }} €</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'pagado') bg-green-100 text-green-800
                                                @elseif($order->status === 'pendiente') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'enviado') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('order.success', $order->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Ver detalles →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-4 text-gray-600">Aún no has realizado ningún pedido</p>
                        <a href="{{ route('home') }}" 
                           class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Comenzar a comprar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
