<x-app-layout>
    {{-- 1. Cabecera de Oferta --}}
    <div class="bg-indigo-600 text-white text-center py-4 font-bold text-lg shadow-inner">
        🔥 ¡OFERTA DE NAVIDAD 2025! 20% de descuento en la segunda unidad 🔥
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Sección 1: Deporte --}}
        <section class="mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b-2 border-indigo-500 inline-block">Zapatillas de Deporte</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($deporte as $item)
                    @include('tienda.partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>

        {{-- Sección 2: Casual --}}
        <section class="mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b-2 border-green-500 inline-block">Estilo Casual</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($casual as $item)
                    @include('tienda.partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>

        {{-- Sección 3: Botas --}}
        <section class="mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b-2 border-yellow-600 inline-block">Botas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($botas as $item)
                    @include('tienda.partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
