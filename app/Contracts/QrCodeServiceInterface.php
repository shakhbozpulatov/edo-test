<?php

namespace App\Contracts;

use App\Models\Document;

interface QrCodeServiceInterface
{
    public function generate(Document $document): string;

    public function getBase64(Document $document): ?string;
}
