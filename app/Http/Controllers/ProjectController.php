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
     *     @OA\Response(response=200, description="Lista de proyectos")
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
     *     @OA\Response(response=201, description="Proyecto creado")
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
     *     @OA\Response(response=200, description="Detalles del proyecto")
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
     *     @OA\Response(response=200, description="Proyecto actualizado")
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
     *     @OA\Response(response=200, description="Proyecto eliminado")
     * )
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return response()->json(['message' => 'Proyecto eliminado exitosamente']);
    }
}
