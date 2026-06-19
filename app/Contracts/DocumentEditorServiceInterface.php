<?php

namespace App\Contracts;

use App\Models\Document;

interface DocumentEditorServiceInterface
{
    public function buildWithQr(Document $document): string;

    public function extractHtml(Document $document): string;

    public function saveFromHtml(Document $document, string $html): void;
}
