<?php

namespace App\Contracts;

use App\Models\Document;
use App\Models\DocumentAttachment;
use Illuminate\Http\UploadedFile;

interface AttachmentServiceInterface
{
    public function storeAsZip(Document $document, UploadedFile $file): DocumentAttachment;

    public function delete(DocumentAttachment $attachment): void;
}
