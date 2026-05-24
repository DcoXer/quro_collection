<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'         => ['nullable', 'string', 'max:20'],
            'province_id'   => ['nullable', 'string', 'max:10'],
            'province_name' => ['nullable', 'string', 'max:100'],
            'city_id'       => ['nullable', 'string', 'max:10'],
            'city_name'     => ['nullable', 'string', 'max:100'],
            'district_id'   => ['nullable', 'string', 'max:10'],
            'district_name' => ['nullable', 'string', 'max:100'],
            'village_id'    => ['nullable', 'string', 'max:10'],
            'village_name'  => ['nullable', 'string', 'max:100'],
            'address_detail'=> ['nullable', 'string', 'max:500'],
        ];
    }
}
