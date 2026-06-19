<?php

namespace App\Http\Controllers\Document;

use App\Contracts\DocumentEditorServiceInterface;
use App\Contracts\QrCodeServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentEditorController extends Controller
{
    public function __construct(
        private readonly DocumentEditorServiceInterface $editorService,
        private readonly QrCodeServiceInterface        $qrCodeService,
    ) {}

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        abort_unless($document->main_file_path, 422, 'Asosiy hujjat fayli mavjud emas.');

        try {
            $htmlContent = $this->editorService->extractHtml($document);
        } catch (\Throwable) {
            $htmlContent = '<p>Hujjat matnini yuklab bo\'lmadi. Yangi matn yozing.</p>';
        }

        return view('documents.editor', [
            'document'    => $document,
            'qrBase64'    => $this->qrCodeService->getBase64($document),
            'htmlContent' => $htmlContent,
        ]);
    }

    public function saveContent(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'html' => ['required', 'string', 'max:5000000'],
        ]);

        $this->editorService->saveFromHtml($document, $validated['html']);

        return response()->json(['success' => true, 'message' => 'Hujjat muvaffaqiyatli saqlandi.']);
    }

    public function content(Document $document)
    {
        $this->authorize('view', $document);

        $path = storage_path('app/' . $document->main_file_path);
        abort_unless(file_exists($path), 404, 'Fayl topilmadi.');

        return response()->file($path, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline',
        ]);
    }

    public function downloadWithQr(Document $document)
    {
        $this->authorize('view', $document);

        $tmpPath  = $this->editorService->buildWithQr($document);
        $fileName = 'QR_' . ($document->main_file_name ?? 'hujjat.docx');

        return response()
            ->download($tmpPath, $fileName)
            ->deleteFileAfterSend();
    }
}
