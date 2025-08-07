<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'stirng',
                'min:3',
                'max:100',
                Rule::unique('project', 'name')->ignore($this->project)
            ],
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
