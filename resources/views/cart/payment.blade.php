<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Realizar Pago - Transferencia Bancaria
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Alerta informativa -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Tu pedido #{{ $order->id }} ha sido creado. Realiza la transferencia bancaria con los siguientes datos:
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Datos bancarios -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Datos para la transferencia:</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Beneficiario:</span>
                            <span class="text-gray-700">Tienda de Zapatillas S.L.</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">IBAN:</span>
                            <span class="text-gray-700 font-mono">ES12 1234 5678 9012 3456 7890</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Concepto:</span>
                            <span class="text-gray-700 font-semibold">PEDIDO-{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Importe:</span>
                            <span class="text-2xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} €</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-4">
                         <strong>Importante:</strong> Incluye el número de pedido en el concepto para identificar tu pago.
                    </p>
                </div>

                <!-- Simulación de pago -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <h4 class="font-semibold text-yellow-800 mb-2">Modo Simulación</h4>
                    <p class="text-sm text-yellow-700 mb-4">
                        En un entorno real, deberías realizar la transferencia desde tu banco. 
                        Para este proyecto, pulsa el botón para simular que has completado el pago.
                    </p>
                </div>

                <!-- Botones -->
                <form action="{{ route('order.confirm-payment', $order->id) }}" method="POST">
                    @csrf
                    <div class="flex space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg text-center hover:bg-gray-400">
                            Pagar más tarde
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                            ✓ He realizado el pago
                        </button>
                    </div>
                </form>

                <p class="text-xs text-gray-500 text-center mt-4">
                    Recibirás un email de confirmación una vez procesemos tu pago
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
