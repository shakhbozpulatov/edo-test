<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    public function rules(): array
    {
        $maxKb = (int) config('filemanager.max_size_kb', 20480);

        return [
            'files'   => ['required'],
            'files.*' => ['file', "max:{$maxKb}"],
        ];
    }

    public function messages(): array
    {
        $maxMb = round(config('filemanager.max_size_kb', 20480) / 1024);

        return [
            'files.*.max' => "Fayl hajmi {$maxMb} MB dan oshmasligi kerak.",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
