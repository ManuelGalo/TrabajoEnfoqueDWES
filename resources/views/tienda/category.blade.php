<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
       {{-- Navegación superior RESPONSIVE --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="/" class="text-gray-500 hover:text-indigo-600 transition">Inicio</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-indigo-600 font-bold capitalize">{{ $category }}</li>
                </ol>
            </nav>
            <span class="text-gray-500 text-sm">{{ $products->total() }} productos</span>
        </div>
                
        {{-- Filtros de búsqueda RESPONSIVE --}}
        <form action="{{ url()->current() }}" method="GET" class="mb-8 bg-gray-50 p-4 rounded-lg">
            
            {{-- Contenedor responsive: stack vertical en móvil, grid en tablets, flex en desktop --}}
            <div class="flex flex-col sm:grid sm:grid-cols-2 md:flex md:flex-row gap-3">
                
                {{-- Select Género --}}
                <select name="gender" 
                        onchange="this.form.submit()"
                        class="rounded-md border-gray-300 w-full md:w-auto">
                    <option value="">Género</option>
                    <option value="hombre" {{ request('gender') == 'hombre' ? 'selected' : '' }}>Hombre</option>
                    <option value="mujer" {{ request('gender') == 'mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
                
                {{-- Select Talla --}}
                <select name="size" 
                        onchange="this.form.submit()" 
                        class="rounded-md border-gray-300 w-full md:w-auto">
                    <option value="">Todas las tallas</option>
                    @foreach(\App\Models\ProductSize::distinct()->orderBy('size')->pluck('size') as $talla)
                        <option value="{{ $talla }}" {{ request('size') == $talla ? 'selected' : '' }}>
                            Talla {{ $talla }}
                        </option>
                    @endforeach
                </select>
                
                {{-- Contenedor de precios: juntos en una fila incluso en móvil --}}
                <div class="flex gap-2 col-span-2 sm:col-span-2 md:col-span-1">
                    <input type="number" 
                        name="min_price" 
                        placeholder="Precio mín" 
                        value="{{ request('min_price') }}"
                        class="rounded-md border-gray-300 w-full md:w-28">
                    <input type="number" 
                        name="max_price" 
                        placeholder="Precio máx" 
                        value="{{ request('max_price') }}"
                        class="rounded-md border-gray-300 w-full md:w-28">
                </div>
                
                {{-- Botones: full width en móvil, auto en desktop --}}
                <div class="flex gap-2 col-span-2 sm:col-span-2 md:col-span-1">
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex-1 md:flex-none transition">
                        Filtrar
                    </button>
                    <a href="{{ url()->current() }}" 
                    class="text-gray-600 hover:text-gray-900 py-2 px-4 bg-white rounded-md border border-gray-300 text-center transition">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>



       {{-- Título RESPONSIVE --}}
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 mb-10 capitalize border-l-4 md:border-l-8 border-indigo-500 pl-3 md:pl-4">
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

