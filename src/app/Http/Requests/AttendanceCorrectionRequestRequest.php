<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCorrectionRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可が必要なら変更（今回はログイン済みならOKと想定）
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'note' => ['nullable', 'string', 'max:1000'],

            // 複数休憩用の配列バリデーション
            'breaks' => ['nullable', 'array'],
            'breaks.*.rest_start_time' => ['required_with:breaks.*.rest_end_time', 'date_format:H:i'],
            'breaks.*.rest_end_time' => ['required_with:breaks.*.rest_start_time', 'date_format:H:i', 'after:breaks.*.rest_start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => '日付は必須です。',
            'start_time.date_format' => '出勤時間の形式が正しくありません（例: 09:00）。',
            'end_time.date_format' => '退勤時間の形式が正しくありません（例: 18:00）。',
            'end_time.after_or_equal' => '退勤時間は出勤時間以降にしてください。',
            'breaks.*.rest_start_time.required_with' => '休憩開始時間が必要です。',
            'breaks.*.rest_end_time.required_with' => '休憩終了時間が必要です。',
            'breaks.*.rest_end_time.after' => '休憩終了時間は開始時間より後にしてください。',
        ];
    }
}
