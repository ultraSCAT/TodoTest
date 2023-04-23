<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    public function index(): JsonResponse {
        try {
            $tasks = DB::table('tasks')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->select('tasks.id', 'tasks.title', 'tasks.completed', 'tasks.created_at',
                    'tasks.updated_at', 'users.name as user_name',
                    DB::raw("IF(tasks.user_id =".auth()->user()->id.", TRUE, FALSE) as is_mine"))
                ->get();
            return response()->json([
                'tasks' => $tasks,
                'amount' => count($tasks) // useful for pagination
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error getting the list of tasks',
                'errors' => $th->getMessage()
            ],504);
        }
    }

    public function show(Task $task): JsonResponse {
        // not using
        try {
            return response()->json($task,200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error getting your task'
            ],500);
        }
    }

    public function store(TaskRequest $request): JsonResponse {
        try {
            $task = new Task();
            $task->fill($request->validated());
            $task->user_id = auth()->user()->id;
            $task->save();
            return response()->json($task,201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error creating your task',
            ], 500);
        }
    }

    public function update(TaskRequest $request, Task $task): JsonResponse {

        try {
            $task->fill($request->validated());
            $task->save();
            return response()->json($task,200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error updating this task',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Task $task): JsonResponse {
        try {
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error deleting this task',
            ], 500);
        }
    }
}
