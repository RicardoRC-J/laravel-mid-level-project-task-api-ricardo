<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|uuid|exists:projects,id',
            'title' => 'required|string|min:3|max:100',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,done',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'required|date|after:today'
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'El ID del proyecto es obligatorio.',
            'project_id.exists' => 'El proyecto seleccionado no existe.',
            'title.required' => 'El título de la tarea es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder 100 caracteres.',
            'status.required' => 'El estado de la tarea es obligatorio.',
            'status.in' => 'El estado debe ser: pending, in_progress o done.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad debe ser: low, medium o high.',
            'due_date.required' => 'La fecha de vencimiento es obligatoria.',
            'due_date.after' => 'La fecha de vencimiento debe ser posterior a hoy.'
        ];
    }
}
