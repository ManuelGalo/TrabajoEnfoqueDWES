<x-app-layout>
    {{-- Cabecera de Oferta --}}
    <div class="bg-indigo-600 text-white text-center py-3 font-bold shadow-md">
        🔥 ¡OFERTA DE NAVIDAD! 20% de descuento 🔥
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-16">
        
        @php
            $secciones = [
                ['id' => 'deporte', 'titulo' => 'Zapatillas de Deporte', 'color' => 'indigo', 'productos' => $deporte],
                ['id' => 'casual', 'titulo' => 'Estilo Casual', 'color' => 'green', 'productos' => $casual],
                ['id' => 'botas', 'titulo' => 'Botas', 'color' => 'yellow', 'productos' => $botas],
            ];
        @endphp

        @foreach($secciones as $seccion)
            <section>
                <div class="flex justify-between items-end mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900 border-b-4 border-{{ $seccion['color'] }}-500">
                        {{ $seccion['titulo'] }}
                    </h2>
                    <a href="{{ route('tienda.category', $seccion['id']) }}" class="text-{{ $seccion['color'] }}-600 font-bold hover:underline">
                        Ver todos →
                    </a>
                </div>

                {{-- Contenedor del Slider --}}
                <div class="flex overflow-x-auto pb-4 space-x-4 snap-x snap-mandatory scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach($seccion['productos'] as $item)
                        <div class="flex-none w-64 snap-start">
                            @include('tienda.partials.product-card', ['product' => $item])
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-app-layout>

