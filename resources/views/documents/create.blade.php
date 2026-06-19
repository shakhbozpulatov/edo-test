@extends('layouts.app')

@section('title', 'Yangi hujjat')
@section('breadcrumb')
<a href="{{ route('documents.index') }}">Hujjatlar</a> / Yangi hujjat
@endsection
@section('page-title', 'Yangi hujjat yaratish')

@section('content')
<div style="max-width:780px;">
<form method="POST" action="{{ route('documents.store') }}" id="docForm">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <h2>Asosiy ma'lumotlar</h2>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Ro'yxatga olish jurnali</label>
                    <select name="registration_journal_id" class="form-control {{ $errors->has('registration_journal_id') ? 'is-invalid' : '' }}">
                        <option value="">— Tanlang —</option>
                        @foreach($journals as $journal)
                            <option value="{{ $journal->id }}" {{ old('registration_journal_id') == $journal->id ? 'selected' : '' }}>
                                {{ $journal->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('registration_journal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Hujjat raqami</label>
                    <input type="text" name="document_number" class="form-control {{ $errors->has('document_number') ? 'is-invalid' : '' }}"
                           value="{{ old('document_number') }}" placeholder="Masalan: CH-2024-001">
                    @error('document_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Qisqa tavsif</label>
                <textarea name="short_description" class="form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }}"
                          rows="3" placeholder="Hujjat haqida qisqacha ma'lumot...">{{ old('short_description') }}</textarea>
                @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- Main document file --}}
    <div class="card mb-4">
        <div class="card-header">
            <h2>Asosiy hujjat fayli</h2>
            <span class="text-xs text-gray">Shablon tanlash orqali DOC/DOCX fayl yarating</span>
        </div>
        <div class="card-body">
            @error('main_file')
                <div class="alert alert-error" style="margin-bottom:12px;">{{ $message }}</div>
            @enderror

            <div id="mainFileArea">
                <div id="noFileMsg" style="{{ session('draft_main_file') ? 'display:none' : '' }}">
                    <button type="button" class="btn btn-secondary" onclick="openModal('templateModal')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Shablon tanlash
                    </button>
                    <p class="text-xs text-gray mt-2">Shablon tanlanmagan. Hujjat saqlanishi uchun shablon tanlash shart.</p>
                </div>

                <div id="fileCard" class="file-card" style="{{ session('draft_main_file') ? '' : 'display:none' }}">
                    <div class="file-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="file-info">
                        <div class="file-name" id="mainFileName">{{ session('draft_main_file.name', '') }}</div>
                        <div class="file-meta">DOCX — Shablon asosida yaratildi</div>
                    </div>
                    <div class="file-actions">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('templateModal')">Almashtirish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional actions --}}
    <div class="card mb-4">
        <div class="card-header"><h2>Qo'shimcha amallar</h2></div>
        <div class="card-body">
            <div class="flex gap-3" style="flex-wrap:wrap;">
                <button type="button" class="btn btn-secondary" onclick="openModal('attachModal')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    Ilovalar
                </button>

                <button type="button" class="btn btn-secondary" onclick="openModal('recipientModal')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Qabul qiluvchilar
                    <span id="recipientCount" style="display:none" class="badge" style="background:#fff;color:var(--primary);border:1px solid var(--primary)">0</span>
                </button>

                <button type="button" class="btn btn-secondary" onclick="openModal('relatedModal')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Aloqador hujjatlar
                </button>
            </div>

            {{-- Attachments list --}}
            <div id="attachmentsList" class="mt-3" style="display:none;">
                <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Ilovalar</div>
                <div id="attachmentsItems"></div>
            </div>

            {{-- Recipients list --}}
            <div id="recipientsList" class="mt-3" style="display:none;">
                <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Qabul qiluvchilar</div>
                <div id="recipientsItems"></div>
            </div>

            {{-- Related docs list --}}
            <div id="relatedList" class="mt-3" style="display:none;">
                <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Aloqador hujjatlar</div>
                <div id="relatedItems"></div>
            </div>
        </div>
    </div>

    <div class="flex gap-3 justify-between">
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Bekor qilish</a>
        <button type="submit" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Saqlash
        </button>
    </div>
</form>
</div>

{{-- Template Modal --}}
<div class="modal-overlay" id="templateModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Shablon tanlash</h3>
            <button class="modal-close" onclick="closeModal('templateModal')">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div id="templatesList" style="display:grid;gap:10px;">
                <div class="text-gray text-sm" style="text-align:center;padding:20px;">Yuklanmoqda...</div>
            </div>
        </div>
    </div>
</div>

{{-- Attachment Modal --}}
<div class="modal-overlay" id="attachModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Ilova qo'shish</h3>
            <button class="modal-close" onclick="closeModal('attachModal')">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <p class="text-sm text-gray mb-4">Fayl tanlang. U ZIP arxivga o'rash va hujjatga biriktiriladi.</p>
            <div id="attachDropZone" style="border:2px dashed #d1d5db;border-radius:8px;padding:32px;text-align:center;cursor:pointer;transition:border-color .15s;"
                 onclick="document.getElementById('attachFileInput').click()"
                 ondragover="event.preventDefault();this.style.borderColor='#1a56db'"
                 ondragleave="this.style.borderColor='#d1d5db'"
                 ondrop="handleDrop(event)">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 8px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <div class="text-sm text-gray">Faylni bu yerga tashlang yoki <span style="color:#1a56db;font-weight:500;">tanlang</span></div>
                <div class="text-xs text-gray mt-1">Maksimal hajm: 50 MB</div>
                <input type="file" id="attachFileInput" style="display:none" onchange="handleAttachFile(this.files[0])">
            </div>
            <div id="pendingAttachList" style="margin-top:12px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('attachModal')">Yopish</button>
        </div>
    </div>
</div>

{{-- Recipients Modal --}}
@include('documents._recipients_modal')

{{-- Related Documents Modal --}}
<div class="modal-overlay" id="relatedModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Aloqador hujjatlar</h3>
            <button class="modal-close" onclick="closeModal('relatedModal')">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            @if($myDocs->isEmpty())
                <p class="text-sm text-gray">Sizning hujjatlaringiz topilmadi.</p>
            @else
                <div style="display:grid;gap:8px;">
                    @foreach($myDocs as $doc)
                        <label style="display:flex;align-items:center;gap:10px;padding:10px;border:1px solid #e5e7eb;border-radius:8px;cursor:pointer;font-size:13px;">
                            <input type="checkbox" name="related_document_ids[]" value="{{ $doc->id }}"
                                   onchange="syncRelated(this)" style="width:auto;">
                            <div>
                                <div class="font-semibold">{{ $doc->document_number ?: 'Raqamsiz' }}</div>
                                <div class="text-gray" style="font-size:11px;">{{ Str::limit($doc->short_description, 60) }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeModal('relatedModal')">Tasdiqlash</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// State
const pendingAttachments = [];
const selectedRecipients = {};  // id => name
const selectedRelated    = {};  // id => text

// Templates
document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
});

function loadTemplates() {
    fetch('{{ route('templates.index') }}', { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
        .then(r => r.json())
        .then(templates => {
            const container = document.getElementById('templatesList');
            if (!templates.length) {
                container.innerHTML = '<p class="text-sm text-gray" style="text-align:center">Shablon topilmadi.</p>';
                return;
            }
            container.innerHTML = templates.map(t => `
                <div onclick="selectTemplate(${t.id}, '${t.name}')"
                     style="padding:14px;border:1.5px solid var(--gray-200);border-radius:8px;cursor:pointer;transition:all .15s;"
                     onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-lt)'"
                     onmouseout="this.style.borderColor='var(--gray-200)';this.style.background=''">
                    <div style="font-size:14px;font-weight:700;color:var(--gray-800);">${t.name}</div>
                    ${t.description ? `<div style="font-size:12px;color:var(--gray-500);margin-top:4px;">${t.description}</div>` : ''}
                </div>
            `).join('');
        });
}

function selectTemplate(id, name) {
    const docNumber   = document.querySelector('[name="document_number"]').value;
    const description = document.querySelector('[name="short_description"]').value;

    fetch(`/templates/${id}/select`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ document_number: docNumber, short_description: description }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('mainFileName').textContent = data.file_name;
            document.getElementById('noFileMsg').style.display  = 'none';
            document.getElementById('fileCard').style.display   = '';
            closeModal('templateModal');
        }
    });
}

// Attachments (stored in session-backed pending list for new doc)
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('attachDropZone').style.borderColor = '#d1d5db';
    if (e.dataTransfer.files[0]) handleAttachFile(e.dataTransfer.files[0]);
}

function handleAttachFile(file) {
    if (!file) return;
    // For create form: store pending and show in UI (actual upload happens after doc is created OR on save)
    const entry = { name: file.name, size: file.size, file };
    pendingAttachments.push(entry);

    const list = document.getElementById('pendingAttachList');
    const div  = document.createElement('div');
    div.className = 'file-card mt-2';
    div.style.marginTop = '8px';
    div.innerHTML = `
        <div class="file-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#1a56db"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></div>
        <div class="file-info"><div class="file-name">${file.name}</div><div class="file-meta">ZIP olindi · ${formatBytes(file.size)}</div></div>
    `;
    list.appendChild(div);

    // Show in main list
    addAttachmentToUI({ original_name: file.name, file_size: file.size });
}

function addAttachmentToUI(att) {
    const list = document.getElementById('attachmentsList');
    const items = document.getElementById('attachmentsItems');
    list.style.display = '';

    const div = document.createElement('div');
    div.className = 'file-card';
    div.style.marginBottom = '8px';
    div.innerHTML = `
        <div class="file-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#1a56db"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></div>
        <div class="file-info"><div class="file-name">${att.original_name}</div><div class="file-meta">ZIP arxiv · ${formatBytes(att.file_size)}</div></div>
    `;
    items.appendChild(div);
}

function formatBytes(b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
    return (b/1048576).toFixed(1) + ' MB';
}

// Recipients
function syncRecipient(checkbox, name) {
    if (checkbox.checked) {
        selectedRecipients[checkbox.value] = name;
    } else {
        delete selectedRecipients[checkbox.value];
    }
    updateRecipientsUI();
}

function updateRecipientsUI() {
    const count = Object.keys(selectedRecipients).length;
    const el = document.getElementById('recipientCount');
    el.textContent = count;
    el.style.display = count ? '' : 'none';

    const list  = document.getElementById('recipientsList');
    const items = document.getElementById('recipientsItems');

    if (!count) { list.style.display = 'none'; return; }
    list.style.display = '';
    items.innerHTML = Object.entries(selectedRecipients).map(([id, name]) =>
        `<div style="display:flex;align-items:center;gap:8px;padding:6px 0;font-size:13px;border-bottom:1px solid #f3f4f6;">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            ${name}
        </div>`
    ).join('');
}

// Related
function syncRelated(checkbox) {
    const label = checkbox.closest('label');
    const name  = label.querySelector('.font-semibold').textContent;
    if (checkbox.checked) {
        selectedRelated[checkbox.value] = name;
    } else {
        delete selectedRelated[checkbox.value];
    }

    const list  = document.getElementById('relatedList');
    const items = document.getElementById('relatedItems');
    const count = Object.keys(selectedRelated).length;

    if (!count) { list.style.display = 'none'; return; }
    list.style.display = '';
    items.innerHTML = Object.values(selectedRelated).map(n =>
        `<div style="font-size:13px;padding:5px 0;border-bottom:1px solid #f3f4f6;">${n}</div>`
    ).join('');
}
</script>

@include('documents._org_tree_script')
@endpush
