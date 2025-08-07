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
     *     @OA\Parameter(name="project_id", in="query", description="Filtrar por proyecto", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200, 
     *         description="Lista de tareas",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Mi Tarea"),
     *                 @OA\Property(property="description", type="string", example="Descripción de la tarea"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="project_id", type="integer", example=1),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2025-12-31")
     *             ))
     *         )
     *     )
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
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la tarea a crear",
     *         @OA\JsonContent(
     *             required={"title","description","status","priority","project_id"},
     *             @OA\Property(property="title", type="string", example="Nueva Tarea", description="Título de la tarea"),
     *             @OA\Property(property="description", type="string", example="Descripción detallada de la tarea", description="Descripción de la tarea"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "cancelled"}, example="pending", description="Estado de la tarea"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, example="medium", description="Prioridad de la tarea"),
     *             @OA\Property(property="project_id", type="integer", example=1, description="ID del proyecto al que pertenece la tarea"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-12-31", description="Fecha límite (opcional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201, 
     *         description="Tarea creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Nueva Tarea"),
     *                 @OA\Property(property="description", type="string", example="Descripción detallada de la tarea"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="priority", type="string", example="medium"),
     *                 @OA\Property(property="project_id", type="integer", example=1),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2025-12-31")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Errores de validación")
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Detalles de la tarea",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Mi Tarea"),
     *                 @OA\Property(property="description", type="string", example="Descripción de la tarea"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="project_id", type="integer", example=1),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2025-12-31"),
     *                 @OA\Property(property="project", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Proyecto Ejemplo"),
     *                     @OA\Property(property="status", type="string", example="active")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tarea no encontrada")
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la tarea a actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Tarea Actualizada", description="Título de la tarea"),
     *             @OA\Property(property="description", type="string", example="Nueva descripción de la tarea", description="Descripción de la tarea"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "cancelled"}, example="in_progress", description="Estado de la tarea"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, example="high", description="Prioridad de la tarea"),
     *             @OA\Property(property="project_id", type="integer", example=1, description="ID del proyecto al que pertenece la tarea"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-12-31", description="Fecha límite")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Tarea actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Tarea Actualizada"),
     *                 @OA\Property(property="description", type="string", example="Nueva descripción de la tarea"),
     *                 @OA\Property(property="status", type="string", example="in_progress"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="project_id", type="integer", example=1),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2025-12-31")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tarea no encontrada"),
     *     @OA\Response(response=422, description="Errores de validación")
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Tarea eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tarea eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tarea no encontrada")
     * )
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return response()->json(['message' => 'Tarea eliminada exitosamente']);
    }
}
