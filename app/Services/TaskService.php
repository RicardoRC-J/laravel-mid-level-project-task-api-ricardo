<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function getFilteredTasks(Request $request)
    {
        $query = Task::query();

        // Aplicar filtros dinámicos
        $query->filterByStatus($request->get('status'))
            ->filterByPriority($request->get('priority'))
            ->filterByProject($request->get('project_id'))
            ->filterByTitle($request->get('title'))
            ->filterByDueDateRange(
                $request->get('due_date_from'),
                $request->get('due_date_to')
            );

        // Incluir proyecto si se solicita
        if ($request->get('with_project')) {
            $query->with('project');
        }

        return $query->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->paginate($request->get('per_page', 15));
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data)
    {
        $task->update($data);
        return $task->fresh();
    }

    public function deleteTask(Task $task)
    {
        return $task->delete();
    }
}
