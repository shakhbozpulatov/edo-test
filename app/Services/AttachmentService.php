<?php

namespace App\Services;

use App\Contracts\AttachmentServiceInterface;
use App\Models\Document;
use App\Models\DocumentAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ZipArchive;

class AttachmentService implements AttachmentServiceInterface
{
    public function storeAsZip(Document $document, UploadedFile $file): DocumentAttachment
    {
        $zipName  = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.zip';
        $filePath = 'attachments/' . $document->user_id . '/' . $zipName;
        $fullPath = storage_path('app/' . $filePath);

        $this->ensureDirectory(dirname($fullPath));
        $this->createZip($fullPath, $file);

        return DocumentAttachment::create([
            'document_id'   => $document->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path'     => $filePath,
            'file_name'     => $zipName,
            'mime_type'     => 'application/zip',
            'file_size'     => filesize($fullPath),
            'type'          => 'attachment',
        ]);
    }

    public function delete(DocumentAttachment $attachment): void
    {
        $path = storage_path('app/' . $attachment->file_path);
        if (file_exists($path)) {
            @unlink($path);
        }

        $attachment->delete();
    }

    private function createZip(string $zipPath, UploadedFile $file): void
    {
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        $zip->addFile($file->getPathname(), $file->getClientOriginalName());
        $zip->close();
    }

    private function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
