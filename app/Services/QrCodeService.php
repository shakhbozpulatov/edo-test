<?php

namespace App\Services;

use App\Contracts\QrCodeServiceInterface;
use App\Models\Document;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService implements QrCodeServiceInterface
{
    public function generate(Document $document): string
    {
        $qrCode = new QrCode($this->buildPayload($document), size: 200, margin: 10);

        $result = (new PngWriter())->write($qrCode);

        $filePath = 'qrcodes/' . $document->user_id . '/qr_' . $document->id . '.png';
        $fullPath = storage_path('app/' . $filePath);

        $this->ensureDirectory(dirname($fullPath));
        file_put_contents($fullPath, $result->getString());

        return $filePath;
    }

    public function getBase64(Document $document): ?string
    {
        if (!$document->qr_code_path) {
            return null;
        }

        $path = storage_path('app/' . $document->qr_code_path);

        return file_exists($path)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($path))
            : null;
    }

    private function buildPayload(Document $document): string
    {
        return json_encode([
            'id'     => $document->id,
            'number' => $document->document_number,
            'date'   => $document->created_at?->format('d.m.Y'),
            'status' => $document->status?->value,
            'user'   => $document->user->name ?? '',
            'url'    => url('/documents/' . $document->id),
        ]);
    }

    private function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
