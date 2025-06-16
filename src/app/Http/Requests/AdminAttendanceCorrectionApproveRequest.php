<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class AdminAttendanceCorrectionApproveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_start' => ['required', 'date_format:H:i'],
            'work_end' => ['required', 'date_format:H:i'],
            'break_times' => ['nullable', 'array'],
            'break_start_times.*' => ['nullable', 'date_format:H:i'],
            'break_end_times.*' => ['nullable', 'date_format:H:i'],
            'reason' => ['required', 'string'],
            'reviewed_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'work_start.required' => '出勤時間を入力してください。',
            'work_end.required' => '退勤時間を入力してください。',
            'work_start.date_format' => '出勤時間の形式が不正です。',
            'work_end.date_format' => '退勤時間の形式が不正です。',
            'break_start_times.0' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_start_times.*.date_format' => '休憩開始時間の形式が不正です。',
            'break_end_times.*.date_format' => '休憩終了時間の形式が不正です。',
            'reason.required' => '備考を記入してください。',
            'reviewed_at.date' => '承認日時の形式が正しくありません。',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $workStart = Carbon::createFromFormat('H:i', $this->input('work_start'));
            $workEnd = Carbon::createFromFormat('H:i', $this->input('work_end'));

            if ($workStart >= $workEnd) {
                $validator->errors()->add('work_start', '出勤時間もしくは退勤時間が不適切な値です');
                $validator->errors()->add('work_end', '出勤時間もしくは退勤時間が不適切な値です');
            }

            $breakStarts = $this->input('break_start_times', []);
            $breakEnds = $this->input('break_end_times', []);

            foreach ($breakStarts as $index => $start) {
                if ($start) {
                    $breakStart = Carbon::createFromFormat('H:i', $start);
                    if ($breakStart > $workEnd) {
                        $validator->errors()->add("break_start_times.$index", '出勤時間もしくは退勤時間が不適切な値です');
                    }
                }
            }

            foreach ($breakEnds as $index => $end) {
                if ($end) {
                    $breakEnd = Carbon::createFromFormat('H:i', $end);
                    if ($breakEnd > $workEnd) {
                        $validator->errors()->add("break_end_times.$index", '出勤時間もしくは退勤時間が不適切な値です');
                    }
                }
            }
        });
    }
}
