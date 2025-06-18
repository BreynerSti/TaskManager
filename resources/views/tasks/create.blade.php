<x-app-layout>


    <form action="{{ route('tasks.create') }}" method="POST">
        @csrf
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 m-6">

            <h1 class="text-center text-3xl font-bold mb-6">Nueva Tarea</h1>


             <!-- Mostrar errores de validación -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label name="title" class="block text-gray-700 text-sm font-bold mb-2">Titulo :</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
            </div>
            <div class="mb-4">
                <label name="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción :</label>
                <textarea name="description" id="description"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black"
                    rows="4"> {{old('description')}} </textarea>
            </div>

            <div class="mb-6">
                <label name="due_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha límite :</label>
                <input type="date" name="due_date" id="due_date" value="{{old('due_date')}}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black">
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 transition-colors font-medium">
                Crear Tarea
            </button>

        </div>

    </form>


</x-app-layout>
