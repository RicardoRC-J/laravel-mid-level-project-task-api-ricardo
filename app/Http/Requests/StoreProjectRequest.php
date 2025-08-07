<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100|unique:projects,name',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del proyecto es obligatorio!!!',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',
            'name.unique' => 'Ya existe un proyecto con este nombre.',
            'status.required' => 'El estado del proyecto es obligatorio.',
            'status.in' => 'El estado debe ser: activo o inactivo.',

        ];
    }
}
