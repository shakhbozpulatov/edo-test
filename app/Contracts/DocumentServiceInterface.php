<?php

namespace App\Contracts;

use App\Models\Document;
use App\Models\DocumentTemplate;

interface DocumentServiceInterface
{
    public function generateFromTemplate(DocumentTemplate $template, Document $document): array;

    public function updateMainFile(Document $document, string $filePath, string $fileName): void;
}
