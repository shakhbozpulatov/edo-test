<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'registration_journal_id' => ['nullable', 'exists:registration_journals,id'],
            'document_number'         => ['nullable', 'string', 'max:100'],
            'short_description'       => ['nullable', 'string'],
            'template_id'             => ['nullable', 'exists:document_templates,id'],
            'recipient_ids'           => ['nullable', 'array'],
            'recipient_ids.*'         => ['exists:organizations,id'],
            'related_document_ids'    => ['nullable', 'array'],
            'related_document_ids.*'  => ['exists:documents,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'registration_journal_id.exists' => 'Tanlangan jurnal mavjud emas.',
        ];
    }
}
