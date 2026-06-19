<?php

namespace App\Http\Controllers\Document;

use App\Contracts\DocumentServiceInterface;
use App\Contracts\QrCodeServiceInterface;
use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\RegistrationJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentServiceInterface $documentService,
        private readonly QrCodeServiceInterface   $qrCodeService,
    ) {}

    public function index(Request $request)
    {
        $query = Auth::user()->documents()
            ->with(['journal', 'recipients', 'attachments'])
            ->whereIn('status', [DocumentStatus::Draft->value, DocumentStatus::Signed->value]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->get();
        $selected  = $this->resolveSelectedDocument($request);

        return view('documents.index', compact('documents', 'selected'));
    }

    public function signed(Request $request)
    {
        $documents = Auth::user()->documents()
            ->with(['journal', 'recipients', 'attachments'])
            ->where('status', DocumentStatus::Signed->value)
            ->latest('signed_at')
            ->get();

        $selected = $this->resolveSelectedDocument($request, DocumentStatus::Signed->value);

        return view('documents.signed', compact('documents', 'selected'));
    }

    public function create()
    {
        $journals  = RegistrationJournal::where('is_active', true)->get();
        $templates = DocumentTemplate::where('is_active', true)->get();
        $myDocs    = $this->userDocumentOptions();

        return view('documents.create', compact('journals', 'templates', 'myDocs'));
    }

    public function store(StoreDocumentRequest $request)
    {
        if (!$request->session()->has('draft_main_file')) {
            return back()->withInput()
                ->withErrors(['main_file' => 'Asosiy hujjat fayli talab qilinadi.']);
        }

        $sessionFile = $request->session()->get('draft_main_file');

        $document = Document::create(array_merge(
            $request->safe()->except(['recipient_ids', 'related_document_ids']),
            [
                'user_id'        => Auth::id(),
                'status'         => DocumentStatus::Draft->value,
                'main_file_path' => $sessionFile['path'],
                'main_file_name' => $sessionFile['name'],
                'template_id'    => $sessionFile['template_id'] ?? null,
            ]
        ));

        $this->syncRecipients($document, $request->input('recipient_ids', []));
        $this->syncRelated($document, $request->input('related_document_ids', []));

        $document->update(['qr_code_path' => $this->qrCodeService->generate($document)]);

        $request->session()->forget('draft_main_file');

        return redirect()->route('documents.index', ['doc' => $document->id])
            ->with('success', 'Hujjat muvaffaqiyatli saqlandi.');
    }

    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        $document->load(['journal', 'template', 'attachments', 'recipients', 'relatedDocuments']);

        return view('documents.edit', [
            'document'  => $document,
            'journals'  => RegistrationJournal::where('is_active', true)->get(),
            'templates' => DocumentTemplate::where('is_active', true)->get(),
            'myDocs'    => $this->userDocumentOptions($document->id),
            'qrBase64'  => $this->qrCodeService->getBase64($document),
        ]);
    }

    public function update(StoreDocumentRequest $request, Document $document)
    {
        $this->authorize('update', $document);

        $document->update($request->safe()->except(['recipient_ids', 'related_document_ids']));

        $this->syncRecipients($document, $request->input('recipient_ids', []));
        $this->syncRelated($document, $request->input('related_document_ids', []));

        $document->update(['qr_code_path' => $this->qrCodeService->generate($document)]);

        return redirect()->route('documents.index', ['doc' => $document->id])
            ->with('success', 'Hujjat yangilandi.');
    }

    public function sign(Document $document)
    {
        $this->authorize('update', $document);

        abort_unless($document->main_file_path, 422, 'Asosiy hujjat fayli mavjud emas.');

        if ($document->recipients()->count() === 0) {
            return back()->with('warning', 'Hujjat imzolandi, ammo qabul qiluvchilar tanlanmagan. Hujjatni tahrirlash orqali qabul qiluvchilarni qo\'shing.');
        }

        $document->update([
            'status'    => DocumentStatus::Signed->value,
            'signed_at' => now(),
        ]);

        return redirect()->route('documents.signed', ['doc' => $document->id])
            ->with('success', 'Hujjat muvaffaqiyatli imzolandi.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->update(['status' => DocumentStatus::Deleted->value]);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Hujjat o\'chirildi.');
    }

    public function downloadMain(Document $document)
    {
        $this->authorize('view', $document);

        $path = storage_path('app/' . $document->main_file_path);
        abort_unless(file_exists($path), 404, 'Fayl topilmadi.');

        return response()->download($path, $document->main_file_name);
    }

    public function updateQrPosition(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'qr_position'      => ['required', 'array'],
            'qr_position.page' => ['required', 'integer', 'min:1'],
            'qr_position.x'    => ['required', 'numeric'],
            'qr_position.y'    => ['required', 'numeric'],
        ]);

        $document->update(['qr_position' => $validated['qr_position']]);

        return response()->json(['success' => true]);
    }

    private function resolveSelectedDocument(Request $request, ?string $status = null): ?Document
    {
        if (!$request->filled('doc')) {
            return null;
        }

        $query = Auth::user()->documents()
            ->with(['journal', 'template', 'attachments', 'recipients.region', 'recipients.district', 'relatedDocuments']);

        if ($status) {
            $query->where('status', $status);
        }

        $selected = $query->findOrFail($request->doc);
        $selected->qr_base64 = $this->qrCodeService->getBase64($selected);

        return $selected;
    }

    private function syncRecipients(Document $document, array $ids): void
    {
        $document->recipients()->sync(array_filter($ids));
    }

    private function syncRelated(Document $document, array $ids): void
    {
        $document->relatedDocuments()->sync(array_filter($ids));
    }

    private function userDocumentOptions(?int $excludeId = null)
    {
        $query = Auth::user()->documents()
            ->whereIn('status', [DocumentStatus::Draft->value, DocumentStatus::Signed->value])
            ->select('id', 'document_number', 'short_description')
            ->latest();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }
}
