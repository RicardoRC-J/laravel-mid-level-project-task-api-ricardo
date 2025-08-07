<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Obtener lista de tareas",
     *     tags={"Tareas"},
     *     @OA\Parameter(name="status", in="query", description="Filtrar por estado", @OA\Schema(type="string")),
     *     @OA\Parameter(name="priority", in="query", description="Filtrar por prioridad", @OA\Schema(type="string")),
     *     @OA\Parameter(name="project_id", in="query", description="Filtrar por proyecto", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista de tareas")
     * )
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getFilteredTasks($request);
        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Crear una nueva tarea",
     *     tags={"Tareas"},
     *     @OA\Response(response=201, description="Tarea creada")
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        return new TaskResource($task);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Obtener detalles de una tarea",
     *     tags={"Tareas"},
     *     @OA\Response(response=200, description="Detalles de la tarea")
     * )
     */
    public function show(Task $task)
    {
        $task->load('project');
        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Actualizar una tarea",
     *     tags={"Tareas"},
     *     @OA\Response(response=200, description="Tarea actualizada")
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->updateTask($task, $request->validated());
        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Eliminar una tarea",
     *     tags={"Tareas"},
     *     @OA\Response(response=200, description="Tarea eliminada")
     * )
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return response()->json(['message' => 'Tarea eliminada exitosamente']);
    }
}
