<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'registration_journal_id', 'document_number', 'short_description',
        'status', 'main_file_path', 'main_file_name', 'template_id',
        'qr_code_path', 'qr_position', 'signed_at',
    ];

    protected $casts = [
        'status'      => DocumentStatus::class,
        'qr_position' => 'array',
        'signed_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journal()
    {
        return $this->belongsTo(RegistrationJournal::class, 'registration_journal_id');
    }

    public function template()
    {
        return $this->belongsTo(DocumentTemplate::class);
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function recipients()
    {
        return $this->belongsToMany(Organization::class, 'document_recipients');
    }

    public function relatedDocuments()
    {
        return $this->belongsToMany(
            Document::class,
            'related_documents',
            'document_id',
            'related_document_id'
        );
    }

    public function isDraft(): bool
    {
        return $this->status === DocumentStatus::Draft;
    }

    public function isSigned(): bool
    {
        return $this->status === DocumentStatus::Signed;
    }
}
