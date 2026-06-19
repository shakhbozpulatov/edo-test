<?php

namespace App\Http\Controllers\Document;

use App\Contracts\AttachmentServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    public function __construct(private readonly AttachmentServiceInterface $attachmentService) {}

    public function store(Request $request, Document $document): JsonResponse
    {
        $this->authorize('update', $document);

        $request->validate([
            'file' => ['required', 'file', 'max:51200'],
        ]);

        $attachment = $this->attachmentService->storeAsZip($document, $request->file('file'));

        return response()->json([
            'success'       => true,
            'id'            => $attachment->id,
            'original_name' => $attachment->original_name,
            'file_name'     => $attachment->file_name,
            'file_size'     => $attachment->file_size,
        ]);
    }

    public function download(Document $document, DocumentAttachment $attachment): BinaryFileResponse
    {
        $this->authorize('view', $document);
        abort_unless($attachment->document_id === $document->id, 403);

        $path = storage_path('app/' . $attachment->file_path);
        abort_unless(file_exists($path), 404, 'Fayl topilmadi.');

        return response()->download($path, $attachment->file_name);
    }

    public function destroy(Document $document, DocumentAttachment $attachment): JsonResponse
    {
        $this->authorize('update', $document);
        abort_unless($attachment->document_id === $document->id, 403);

        $this->attachmentService->delete($attachment);

        return response()->json(['success' => true]);
    }
}
