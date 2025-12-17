<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Navegación superior --}}
        <div class="flex items-center justify-between mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="/" class="text-gray-500 hover:text-indigo-600 transition">Inicio</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-indigo-600 font-bold capitalize">{{ $category }}</li>
                </ol>
            </nav>
            <span class="text-gray-500 text-sm">{{ $products->total() }} productos encontrados</span>
        </div>

        {{-- Título de la Sección --}}
        <h1 class="text-4xl font-black text-gray-900 mb-10 capitalize border-l-8 border-indigo-500 pl-4">
            {{ $category }}
        </h1>

        {{-- Grid de Productos con el mismo tamaño que el Slider --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($products as $product)
                {{-- Al usar el mismo partial, garantizamos que el tamaño sea idéntico --}}
                <div class="w-full">
                    @include('tienda.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        {{-- Paginación (Capa de Lógica) --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>

