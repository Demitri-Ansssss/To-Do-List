<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        return TodoResource::collection(Todo::latest()->get());
    }

    public function show($id)
    {
        $todo = Todo::find($id);
        if (! $todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return new TodoResource($todo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'status' => 'nullable|string',
        ]);

        $todo = Todo::create([
            'name' => $request->name,
            'status' => $request->status ?? 'List Tugas',
        ]);

        return new TodoResource($todo);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|min:3',
            'status' => 'sometimes|required|string',
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update($request->all());

        return new TodoResource($todo);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}
