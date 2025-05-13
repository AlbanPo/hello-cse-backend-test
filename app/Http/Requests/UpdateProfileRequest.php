<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProfileStatus;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Admin $admin */
        $admin = $this->user();
        return $admin->id === $this->profile->admin_id;
    }

    /**
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|max:2048',
            'status' => ['sometimes', new Enum(ProfileStatus::class)],
        ];
    }
}
