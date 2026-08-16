<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Body for `PUT /api/v1/admin/users/{user_id}`. The admin endpoint is
 * intentionally narrower than UpdateUserRequest — only the role is
 * mutable from here. Other profile fields stay on the regular
 * `PUT /api/v1/users/{user_id}` path (which is admin-gated by the
 * /admin/* prefix anyway; on /v1/users it's currently open to the
 * user themselves for self-service).
 */
class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ];
    }
}