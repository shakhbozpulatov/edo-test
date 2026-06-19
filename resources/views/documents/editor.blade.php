@extends('layouts.app')

@section('title', 'Hujjat muharriri')

@section('breadcrumb')
<a href="{{ route('documents.index') }}">Hujjatlar</a> /
<a href="{{ route('documents.edit', $document) }}">{{ $document->document_number }}</a> /
Muharrir
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/documents/editor.css') }}">
@endpush

@section('content')
<div class="editor-layout">

    {{-- ─── LEFT TOOL PANEL ─── --}}
    <aside class="tool-panel">

        {{-- Document Info Card --}}
        <div class="tool-card">
            <div class="tool-card-header">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3>Hujjat ma'lumoti</h3>
            </div>
            <div class="tool-card-body" style="padding:14px 16px;">
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:6px;">
                    {{ $document->document_number }}
                </div>
                <div style="font-size:12px;color:var(--gray-600);line-height:1.5;margin-bottom:10px;">
                    {{ Str::limit($document->short_description, 80) }}
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <span class="badge {{ $document->status->badgeClass() }}">{{ $document->status->label() }}</span>
                    @if($document->signed_at)
                        <span style="font-size:11px;color:var(--gray-500);">{{ $document->signed_at->format('d.m.Y') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Save Card --}}
        <div class="tool-card">
            <div class="tool-card-header">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <h3>Saqlash</h3>
            </div>
            <div class="tool-card-body">
                <div id="saveStatusRow" class="status-row idle" style="margin-bottom:12px;">
                    <div class="status-dot gray" id="saveStatusDot"></div>
                    <span id="saveStatusText">Tahrirlash boshlaning</span>
                </div>
                <button class="btn btn-primary" id="saveBtn" onclick="saveDocument()" style="width:100%;justify-content:center;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Hujjatni saqlash
                </button>
                <div style="margin-top:8px;">
                    <a href="{{ route('documents.download-main', $document) }}"
                       class="btn btn-ghost"
                       style="width:100%;justify-content:center;font-size:12px;">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        DOCX yuklab olish
                    </a>
                </div>
            </div>
        </div>

        {{-- QR Code Card --}}
        <div class="tool-card">
            <div class="tool-card-header">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <h3>QR Kod</h3>
            </div>
            <div class="tool-card-body">
                @if($qrBase64)
                    <div class="qr-display">
                        <div class="qr-img-wrap">
                            <img src="{{ $qrBase64 }}" alt="QR Kod">
                        </div>
                        <div class="drag-hint">
                            <strong>Kursorni kerakli joyga qo'ying</strong><br>
                            va "QR Kod qo'yish" tugmasini bosing
                        </div>
                    </div>
                    <button class="btn btn-secondary" onclick="insertQrCode()" style="width:100%;justify-content:center;margin-bottom:8px;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        QR Kod qo'yish
                    </button>
                    <a href="{{ route('documents.download-with-qr', $document) }}"
                       class="btn btn-ghost"
                       style="width:100%;justify-content:center;font-size:12px;{{ $document->qr_position ? '' : 'pointer-events:none;opacity:.4;' }}">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        QR bilan yuklab olish
                    </a>
                @else
                    <div style="text-align:center;padding:20px;color:var(--gray-500);font-size:13px;">
                        QR kod topilmadi.<br>Hujjatni saqlang.
                    </div>
                @endif
            </div>
        </div>

        {{-- Back link --}}
        <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary" style="justify-content:center;">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Tahrirlashga qaytish
        </a>
    </aside>

    {{-- ─── RIGHT: TinyMCE EDITOR ─── --}}
    <div class="editor-wrap">
        <div class="editor-toolbar">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span class="editor-toolbar-title">{{ $document->main_file_name }}</span>
            <span class="editor-mode-badge">Muharrir rejimi</span>
        </div>
        <div class="editor-body">
            <textarea id="editorContent" name="content"></textarea>
        </div>
    </div>
</div>

{{-- Toast notification --}}
<div id="toast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="toastIcon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
{{-- TinyMCE from jsDelivr (self-hosted, no API key required) --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
const DOC_ID       = {{ $document->id }};
const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]').content;
const QR_BASE64    = @json($qrBase64);
const INITIAL_HTML = @json($htmlContent);

// ─── TinyMCE INIT ────────────────────────────────────────────────────────────
tinymce.init({
    selector   : '#editorContent',
    base_url   : 'https://cdn.jsdelivr.net/npm/tinymce@6',
    suffix     : '.min',
    license_key: 'gpl',
    language   : 'ru',

    height  : 680,
    resize  : false,
    menubar : true,
    statusbar: true,

    plugins: 'advlist autolink lists link image table searchreplace wordcount code fullscreen',

    toolbar: [
        'undo redo | bold italic underline strikethrough | fontfamily fontsize',
        'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | link image' + (QR_BASE64 ? ' | insertqr' : '') + ' | fullscreen code'
    ],

    content_style: [
        /* Gray "desktop" around the page */
        'html {',
        '  background: #dce0e8;',
        '  padding: 28px 20px 40px;',
        '  min-height: 100%;',
        '}',
        /* White A4-like page */
        'body {',
        '  font-family: "Times New Roman", Times, serif;',
        '  font-size: 12pt;',
        '  line-height: 1.55;',
        '  width: 640px;',
        '  max-width: 640px;',
        '  margin: 0 auto;',
        '  padding: 56px 64px;',
        '  background: #fff;',
        '  box-shadow: 0 3px 24px rgba(0,0,0,0.18);',
        '  min-height: 860px;',
        '  color: #1a1a1a;',
        '  border-top: 3px solid #0f2c59;',
        '}',
        'p { margin: 0 0 6px; }',
        'table { border-collapse: collapse; width: 100%; margin: 8px 0; }',
        'td, th { border: 1px solid #aaa; padding: 5px 8px; font-size: 10pt; }',
        'th { background: #f3f4f6; font-weight: 700; }',
        'img { max-width: 100%; height: auto; }',
    ].join('\n'),

    image_advtab: true,
    image_title : true,
    automatic_uploads: false,

    setup: function (editor) {
        // Custom QR insertion button
        if (QR_BASE64) {
            editor.ui.registry.addButton('insertqr', {
                text   : 'QR',
                icon   : 'image',
                tooltip: 'QR Kod qo\'yish (kursor joylashgan yerga)',
                onAction: function () {
                    editor.insertContent(
                        '<img src="' + QR_BASE64 + '" ' +
                        'style="width:72pt;height:72pt;display:inline-block;vertical-align:middle;" ' +
                        'alt="QR Kod" />'
                    );
                }
            });
        }

        editor.on('init', function () {
            editor.setContent(INITIAL_HTML || '');
            setSaveStatus('idle', 'Tahrirlash boshlaning');
        });

        editor.on('input change', function () {
            setSaveStatus('unsaved', 'Saqlanmagan o\'zgarishlar bor');
        });
    },
});

// ─── INSERT QR FROM SIDEBAR ──────────────────────────────────────────────────
window.insertQrCode = function () {
    const ed = tinymce.get('editorContent');
    if (!ed) { showToast('Muharrir hali yuklanmadi', 'error'); return; }
    if (!QR_BASE64) { showToast('QR kod topilmadi', 'error'); return; }

    ed.focus();
    ed.insertContent(
        '<img src="' + QR_BASE64 + '" ' +
        'style="width:72pt;height:72pt;display:inline-block;vertical-align:middle;" ' +
        'alt="QR Kod" />'
    );
    setSaveStatus('unsaved', 'Saqlanmagan o\'zgarishlar bor');
    showToast('QR kod qo\'yildi. Saqlashni unutmang!', 'success');
};

// ─── SAVE DOCUMENT ───────────────────────────────────────────────────────────
window.saveDocument = async function () {
    const ed = tinymce.get('editorContent');
    if (!ed) { showToast('Muharrir hali yuklanmadi', 'error'); return; }

    const html    = ed.getContent();
    const saveBtn = document.getElementById('saveBtn');

    saveBtn.disabled    = true;
    saveBtn.textContent = 'Saqlanmoqda...';
    setSaveStatus('idle', 'Saqlanmoqda...');

    try {
        const res = await fetch('/documents/' + DOC_ID + '/save-content', {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept'      : 'application/json',
            },
            body: JSON.stringify({ html }),
        });

        const data = await res.json().catch(() => ({}));

        if (!res.ok) {
            throw new Error(data.message || 'HTTP ' + res.status);
        }

        setSaveStatus('saved', 'Hujjat saqlandi');
        showToast('Hujjat muvaffaqiyatli saqlandi!', 'success');

    } catch (err) {
        setSaveStatus('unsaved', 'Saqlashda xatolik yuz berdi');
        showToast('Xatolik: ' + err.message, 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML =
            '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">' +
            '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>' +
            ' Hujjatni saqlash';
    }
};

// ─── UI HELPERS ──────────────────────────────────────────────────────────────
function setSaveStatus(type, msg) {
    const row  = document.getElementById('saveStatusRow');
    const dot  = document.getElementById('saveStatusDot');
    const text = document.getElementById('saveStatusText');
    if (!row) return;

    row.className  = 'status-row ' + (type === 'saved' ? 'saved' : type === 'unsaved' ? 'unsaved' : 'idle');
    dot.className  = 'status-dot ' + (type === 'saved' ? 'green' : type === 'unsaved' ? 'yellow' : 'gray');
    text.textContent = msg;
}

function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toastIcon');
    toast.className = type;
    document.getElementById('toastMsg').textContent = msg;
    icon.innerHTML  = type === 'error'
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
    toast.style.display = 'flex';
    clearTimeout(toast._t);
    toast._t = setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

// Warn on unsaved changes
window.addEventListener('beforeunload', function (e) {
    const ed = tinymce.get('editorContent');
    if (ed && document.getElementById('saveStatusRow')?.classList.contains('unsaved')) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush
