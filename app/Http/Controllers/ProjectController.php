<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Project Task API",
 *     version="1.0.0",
 *     description="API para gestión de proyectos y tareas"
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor local"
 * )
 */
class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @OA\Get(
     *     path="/projects",
     *     summary="Obtener lista de proyectos",
     *     tags={"Proyectos"},
     *     @OA\Parameter(name="status", in="query", description="Filtrar por estado", @OA\Schema(type="string")),
     *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200, 
     *         description="Lista de proyectos",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Mi Proyecto"),
     *                 @OA\Property(property="description", type="string", example="Descripción del proyecto"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             ))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $projects = $this->projectService->getFilteredProjects($request);
        return ProjectResource::collection($projects);
    }

    /**
     * @OA\Post(
     *     path="/projects",
     *     summary="Crear un nuevo proyecto",
     *     tags={"Proyectos"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del proyecto a crear",
     *         @OA\JsonContent(
     *             required={"name","description","status"},
     *             @OA\Property(property="name", type="string", example="Mi Nuevo Proyecto", description="Nombre del proyecto"),
     *             @OA\Property(property="description", type="string", example="Descripción detallada del proyecto", description="Descripción del proyecto"),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive", "completed"}, example="active", description="Estado del proyecto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201, 
     *         description="Proyecto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Mi Nuevo Proyecto"),
     *                 @OA\Property(property="description", type="string", example="Descripción detallada del proyecto"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return new ProjectResource($project);
    }

    /**
     * @OA\Get(
     *     path="/projects/{id}",
     *     summary="Obtener detalles de un proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Detalles del proyecto",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Mi Proyecto"),
     *                 @OA\Property(property="description", type="string", example="Descripción del proyecto"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="tasks_count", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     */
    public function show(Project $project)
    {
        $project->loadCount('tasks');
        return new ProjectResource($project);
    }

    /**
     * @OA\Put(
     *     path="/projects/{id}",
     *     summary="Actualizar un proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del proyecto a actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Proyecto Actualizado", description="Nombre del proyecto"),
     *             @OA\Property(property="description", type="string", example="Nueva descripción del proyecto", description="Descripción del proyecto"),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive", "completed"}, example="completed", description="Estado del proyecto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Proyecto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Proyecto Actualizado"),
     *                 @OA\Property(property="description", type="string", example="Nueva descripción del proyecto"),
     *                 @OA\Property(property="status", type="string", example="completed")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Proyecto no encontrado"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project = $this->projectService->updateProject($project, $request->validated());
        return new ProjectResource($project);
    }

    /**
     * @OA\Delete(
     *     path="/projects/{id}",
     *     summary="Eliminar un proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Proyecto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proyecto eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return response()->json(['message' => 'Proyecto eliminado exitosamente']);
    }
}
