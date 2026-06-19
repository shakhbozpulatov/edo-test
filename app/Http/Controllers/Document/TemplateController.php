<?php

namespace App\Http\Controllers\Document;

use App\Contracts\DocumentServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function __construct(private readonly DocumentServiceInterface $documentService) {}

    public function index(): JsonResponse
    {
        $templates = DocumentTemplate::where('is_active', true)->get(['id', 'name', 'description']);

        return response()->json($templates);
    }

    public function select(Request $request, DocumentTemplate $template): JsonResponse
    {
        $request->validate([
            'document_id' => ['nullable', 'exists:documents,id'],
        ]);

        if ($request->filled('document_id')) {
            return $this->selectForExistingDocument($request, $template);
        }

        return $this->selectForNewDocument($request, $template);
    }

    private function selectForExistingDocument(Request $request, DocumentTemplate $template): JsonResponse
    {
        $document = Auth::user()->documents()->findOrFail($request->document_id);

        $result = $this->documentService->generateFromTemplate($template, $document);
        $this->documentService->updateMainFile($document, $result['file_path'], $result['file_name']);

        return response()->json([
            'success'   => true,
            'file_name' => $result['file_name'],
            'open_url'  => route('documents.download-main', $document),
        ]);
    }

    private function selectForNewDocument(Request $request, DocumentTemplate $template): JsonResponse
    {
        $tempDocument = $this->buildTemporaryDocument($request);

        $result = $this->documentService->generateFromTemplate($template, $tempDocument);

        $request->session()->put('draft_main_file', [
            'path'        => $result['file_path'],
            'name'        => $result['file_name'],
            'template_id' => $template->id,
        ]);

        return response()->json([
            'success'       => true,
            'file_name'     => $result['file_name'],
            'template_id'   => $template->id,
            'template_name' => $template->name,
        ]);
    }

    private function buildTemporaryDocument(Request $request): Document
    {
        $doc                    = new Document();
        $doc->id                = time();
        $doc->user_id           = Auth::id();
        $doc->document_number   = $request->input('document_number', 'TEMP-' . now()->format('Ymd'));
        $doc->short_description = $request->input('short_description', '');
        $doc->setRelation('user', Auth::user());

        return $doc;
    }
}
