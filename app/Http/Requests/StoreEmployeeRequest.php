<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string|max:20',
            'employee_number' => 'required|string|max:50',
            'date_started' => 'required|date',
            'date_stop' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string|max:255',
        ];
    }
}
