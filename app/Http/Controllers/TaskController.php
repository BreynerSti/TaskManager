<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create()
    {
        return view("tasks.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|min:3|max:255",
            "description" => "required|string|min:10|max:1000",
            "due_date" => "required|date|after_or_equal:today|before_or_equal:" . now()->addYears(2)->format('Y-m-d'),
            "status" => "sometimes|in:pendiente,en_progreso,completado"
        ]);

        // Sanitizar datos antes de guardar
        Task::create([
            "title" => trim($request->title),
            "description" => trim($request->description),
            "status" => $request->status ?? 'pendiente',
            "due_date" => $request->due_date,
            "user_id" => Auth::id()
        ]);

        return redirect()->route("dashboard")->with("success", "Tarea creada con éxito");
    }

    public function index(Request $request)

    {
       
        $query = Task::where('user_id', Auth::id());

    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

     
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

 
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }


           $tasks = $query->orderBy('due_date', 'asc')
                   ->paginate(10)
                   ->appends($request->except('page'));

        return view('tasks.dashboard', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status,
                'search' => $request->search,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ]
        ]);
    }

    public function edit($id)
    {
        $task = Task::find($id);

        // Primero formateo la fecha
        if ($task->due_date) {
            $task->due_date = \Carbon\Carbon::parse($task->due_date)->format('Y-m-d');
        }

        // Después envío los datos a la vista
        return view("tasks.edit", compact("task"));
    }
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Verificar que el usuario puede editar esta tarea
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta tarea.');
        }

        $request->validate([
            "title" => "required|string|min:3|max:255",
            "description" => "required|string|min:10|max:1000",
            "due_date" => "required|date|after_or_equal:today|before_or_equal:" . now()->addYears(2)->format('Y-m-d'),
            "status" => "required|in:pendiente,en_progreso,completado"
        ]);

        // Sanitizar y actualizar
        $task->update([
            "title" => trim($request->title),
            "description" => trim($request->description),
            "status" => $request->status,
            "due_date" => $request->due_date
        ]);

        return redirect()->route("dashboard")->with("success", "Tarea actualizada con éxito");
    }

    public function destroy($id)
    {
        Task::find($id)->delete();
        return redirect()->route("dashboard")->with("success", "eliminado con exito");
    }
}
