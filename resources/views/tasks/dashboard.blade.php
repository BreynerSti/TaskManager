<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="topAlert" class="fixed top-16 left-4 right-4 z-40 alert-slide-down">
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-3 rounded-lg shadow-lg max-w-md mx-auto">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="closeTopAlert()" class="text-white hover:text-gray-200 transition ml-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <script>
            setTimeout(() => closeTopAlert(), 4000);

            function closeTopAlert() {
                const alert = document.getElementById('topAlert');
                if (alert) {
                    alert.classList.add('alert-fade-out-up');
                    setTimeout(() => alert.remove(), 500);
                }
            }
        </script>
    @endif

    <!-- FILTROS - NUEVA SECCIÓN -->
    <div class="mt-8 ml-8 mr-8 mb-6">
        <div class="bg-white rounded-lg border border-gray-300 p-4">
            <h3 class="text-lg font-semibold mb-3 text-gray-800">Filtrar Tareas</h3>

            <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Filtro por Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ $filters['status'] == 'pendiente' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="en_progreso" {{ $filters['status'] == 'en_progreso' ? 'selected' : '' }}>En
                            Progreso</option>
                        <option value="completado" {{ $filters['status'] == 'completado' ? 'selected' : '' }}>Completado
                        </option>
                    </select>
                </div>

                <!-- Filtro por Búsqueda de Título -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar por título:</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}"
                        placeholder="Ej: revisar, documentos..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
                </div>

                <!-- Filtro Fecha Desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desde fecha:</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
                </div>

                <!-- Filtro Fecha Hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasta fecha:</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
                </div>

                <!-- Botones de Acción -->
                <div class="md:col-span-4 flex gap-2 pt-2">
                    <button type="submit"
                        class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                        Aplicar Filtros
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA DE TAREAS -->
    <div class="mt-0 ml-8 mr-8">

        <!-- Contador de resultados -->
        <div class="mb-4">
            <p class="text-sm text-gray-600">
                Mostrando {{ $tasks->count() }} tarea(s)
                @if ($filters['status'] || $filters['search'] || $filters['date_from'] || $filters['date_to'])
                    con filtros aplicados
                @endif
            </p>
        </div>

        <table class="w-full border border-gray-300 bg-white rounded-lg">
            <thead>
                <tr>
                    <th class="text-center border border-gray-400 px-4 py-2">ID</th>
                    <th class="text-center border border-gray-400 px-4 py-2">Título</th>
                    <th class="text-center border border-gray-400 px-4 py-2">Descripción</th>
                    <th class="text-center border border-gray-400 px-4 py-2">Estado</th>
                    <th class="text-center border border-gray-400 px-4 py-2">Fecha límite</th>
                    <th class="text-center border border-gray-400 px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td class="text-center border border-gray-500 px-4 py-2">{{ $task->id }}</td>
                        <td class="text-center border border-gray-500 px-4 py-2">{{ $task->title }}</td>
                        <td class="text-center border border-gray-500 px-4 py-2">{{ $task->description }}</td>
                        <td class="text-center border border-gray-500 px-4 py-2">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium
                                @if ($task->status == 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($task->status == 'en_progreso') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td class="text-center border border-gray-500 px-4 py-2">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</td>
                        <td class="text-center border border-gray-500 px-4 py-2">
                            <div class="flex gap-2 justify-center">
                                <!-- Botón Editar -->
                                <a href="{{ route('tasks.edit', $task->id) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Editar
                                </a>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta tarea?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center border border-gray-500 px-4 py-8 text-gray-500">
                            No se encontraron tareas con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
        <!-- Botón flotante para crear nueva tarea -->
        <div class="border-t border-gray-300 bg-gray-50 p-4 text-center">
            <a href="{{ route('tasks.create') }}"
                class="inline-block bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors font-medium">
                + Registrar Nueva Tarea
            </a>
        </div>
    </div>



</x-app-layout>
