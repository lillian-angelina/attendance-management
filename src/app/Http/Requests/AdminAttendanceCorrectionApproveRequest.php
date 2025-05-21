<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttendanceCorrectionApproveRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 管理者だけ承認可能
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'reviewed_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'reviewed_at.date' => '承認日時の形式が正しくありません。',
        ];
    }
}
