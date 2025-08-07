<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectService
{
    public function getFilteredProjects(Request $request)
    {
        $query = Project::query();

        $query->filterByStatus($request->get('status'))
            ->filterByName($request->get('name'))
            ->filterByDateRange(
                $request->get('created_from'),
                $request->get('created_to')
            );

        if ($request->get('with_tasks_count')) {
            $query->withCount('tasks');
        }

        if ($request->get('with_tasks')) {
            $query->with('tasks');
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));
    }

    public function createProject(array $data)
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data)
    {
        $project->update($data);
        return $project->fresh();
    }

    public function deleteProject(Project $project)
    {
        return $project->delete();
    }
}
