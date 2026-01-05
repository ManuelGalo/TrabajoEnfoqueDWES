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
        
        {{--Filtros de busqueda--}}
        <form action="{{ url()->current() }}" method="GET" class="flex gap-4 mb-8 bg-gray-50 p-4 rounded-lg">
            <select name="gender" class="rounded-md border-gray-300">
                <option value="">Género</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
            </select>
             <select name="size" onchange="this.form.submit()" class="rounded-md border-gray-300">
                <option value="">Todas las tallas</option>
                {{-- Listamos las tallas únicas que existen en la base de datos --}}
                @foreach(\App\Models\ProductSize::distinct()->orderBy('size')->pluck('size') as $talla)
                    <option value="{{ $talla }}" {{ request('size') == $talla ? 'selected' : '' }}>
                        Talla {{ $talla }}
                    </option>
                @endforeach
            </select>
            
            <input type="number" name="min_price" placeholder="Precio mín" class="rounded-md border-gray-300 w-28">
            <input type="number" name="max_price" placeholder="Precio máx" class="rounded-md border-gray-300 w-28">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Filtrar</button>
            <a href="{{ url()->current() }}" class="text-gray-500 py-2">Limpiar</a>
        </form>


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

