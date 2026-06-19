@extends('layouts.app')

@section('title', 'Hujjatni tahrirlash')
@section('breadcrumb')
<a href="{{ route('documents.index') }}">Hujjatlar</a> / Tahrirlash
@endsection
@section('page-title', 'Hujjatni tahrirlash')

@section('topbar-actions')
    <form method="POST" action="{{ route('documents.sign', $document) }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-success btn-sm">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Imzolash
        </button>
    </form>
@endsection

@section('content')
<div style="max-width:780px;">
<form method="POST" action="{{ route('documents.update', $document) }}" id="editForm">
    @csrf @method('PUT')

    <div class="card mb-4">
        <div class="card-header">
            <h2>Asosiy ma'lumotlar</h2>
            <span class="badge {{ $document->status->badgeClass() }}">{{ $document->status->label() }}</span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Ro'yxatga olish jurnali</label>
                    <select name="registration_journal_id" class="form-control">
                        <option value="">— Tanlang —</option>
                        @foreach($journals as $journal)
                            <option value="{{ $journal->id }}" {{ $document->registration_journal_id == $journal->id ? 'selected' : '' }}>
                                {{ $journal->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hujjat raqami</label>
                    <input type="text" name="document_number" class="form-control"
                           value="{{ old('document_number', $document->document_number) }}" placeholder="Masalan: CH-2024-001">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Qisqa tavsif</label>
                <textarea name="short_description" class="form-control" rows="3" placeholder="Hujjat haqida qisqacha ma'lumot...">{{ old('short_description', $document->short_description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Main file --}}
    <div class="card mb-4">
        <div class="card-header">
            <h2>Asosiy hujjat fayli</h2>
        </div>
        <div class="card-body">
            @if($document->main_file_path)
                <div class="file-card">
                    <div class="file-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="file-info">
                        <div class="file-name" id="mainFileName">{{ $document->main_file_name }}</div>
                        <div class="file-meta">DOCX hujjati</div>
                    </div>
                    <div class="file-actions">
                        <a href="{{ route('documents.download-main', $document) }}" class="btn btn-secondary btn-sm">Ochish</a>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('templateModal')">Almashtirish</button>
                    </div>
                </div>
            @else
                <button type="button" class="btn btn-secondary" onclick="openModal('templateModal')">
                    Shablon tanlash
                </button>
            @endif
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
                    <span id="recipientCount" class="badge" style="background:#1a56db;color:#fff;{{ $document->recipients->isEmpty() ? 'display:none' : '' }}">{{ $document->recipients->count() }}</span>
                </button>

                <button type="button" class="btn btn-secondary" onclick="openModal('relatedModal')">
                    Aloqador hujjatlar
                </button>
            </div>

            {{-- Existing attachments --}}
            @if($document->attachments->isNotEmpty())
                <div class="mt-3">
                    <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Mavjud ilovalar</div>
                    @foreach($document->attachments as $att)
                        <div class="file-card" style="margin-bottom:8px;" id="att-{{ $att->id }}">
                            <div class="file-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#1a56db"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </div>
                            <div class="file-info">
                                <div class="file-name">{{ $att->original_name }}</div>
                                <div class="file-meta">ZIP arxiv</div>
                            </div>
                            <div class="file-actions">
                                <a href="{{ route('attachments.download', [$document, $att]) }}" class="btn btn-secondary btn-sm">Yuklab olish</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteAttachment({{ $att->id }})">O'chirish</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- New attachments (uploaded live) --}}
            <div id="newAttachList"></div>

            {{-- Recipients --}}
            <div class="mt-3" id="recipientsSummary" style="{{ $document->recipients->isEmpty() ? 'display:none' : '' }}">
                <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Qabul qiluvchilar</div>
                <div id="recipientsItems">
                    @foreach($document->recipients as $rec)
                        <div style="font-size:13px;padding:5px 0;border-bottom:1px solid #f9fafb;">{{ $rec->name }}</div>
                    @endforeach
                </div>
            </div>

            {{-- Related docs summary --}}
            @if($document->relatedDocuments->isNotEmpty())
                <div class="mt-3">
                    <div class="text-xs text-gray font-semibold mb-2" style="text-transform:uppercase;letter-spacing:.06em;">Aloqador hujjatlar</div>
                    @foreach($document->relatedDocuments as $rel)
                        <div style="font-size:13px;padding:5px 0;border-bottom:1px solid #f9fafb;">{{ $rel->document_number ?: 'Raqamsiz' }} — {{ Str::limit($rel->short_description, 50) }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- QR Section --}}
    @if($qrBase64)
        <div class="card mb-4">
            <div class="card-header"><h2>QR Kod</h2></div>
            <div class="card-body">
                <div style="display:flex;align-items:flex-start;gap:20px;">
                    <div>
                        <img src="{{ $qrBase64 }}" alt="QR" class="qr-img" draggable="true"
                             ondragstart="event.dataTransfer.setData('text/plain','qr-code')"
                             style="width:100px;height:100px;border:1px solid #e5e7eb;border-radius:6px;">
                        <div class="text-xs text-gray mt-2">QR kodni hujjat ichiga<br>sudrab tashlang</div>
                    </div>
                    @if($document->qr_position)
                        <div class="text-sm text-gray">
                            <strong>Joylashuv saqlangan:</strong><br>
                            Sahifa: {{ $document->qr_position['page'] }}<br>
                            X: {{ $document->qr_position['x'] }}, Y: {{ $document->qr_position['y'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="flex gap-3 justify-between">
        <a href="{{ route('documents.index', ['doc' => $document->id]) }}" class="btn btn-secondary">Bekor qilish</a>
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
            <div id="attachDropZone"
                 style="border:2px dashed #d1d5db;border-radius:8px;padding:32px;text-align:center;cursor:pointer;"
                 onclick="document.getElementById('attachFileInput').click()"
                 ondragover="event.preventDefault();this.style.borderColor='#1a56db'"
                 ondragleave="this.style.borderColor='#d1d5db'"
                 ondrop="handleDrop(event)">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 8px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <div class="text-sm text-gray">Faylni bu yerga tashlang yoki <span style="color:#1a56db;font-weight:500;">tanlang</span></div>
                <input type="file" id="attachFileInput" style="display:none" onchange="handleAttachFile(this.files[0])">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('attachModal')">Yopish</button>
        </div>
    </div>
</div>

{{-- Recipients Modal --}}
@include('documents._recipients_modal')

{{-- Related Modal --}}
<div class="modal-overlay" id="relatedModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Aloqador hujjatlar</h3>
            <button class="modal-close" onclick="closeModal('relatedModal')">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            @forelse($myDocs as $doc)
                <label style="display:flex;align-items:center;gap:10px;padding:10px;border:1px solid #e5e7eb;border-radius:8px;cursor:pointer;font-size:13px;margin-bottom:8px;">
                    <input type="checkbox" name="related_document_ids[]" value="{{ $doc->id }}"
                           {{ $document->relatedDocuments->contains('id', $doc->id) ? 'checked' : '' }}
                           style="width:auto;">
                    <div>
                        <div class="font-semibold">{{ $doc->document_number ?: 'Raqamsiz' }}</div>
                        <div class="text-gray text-xs">{{ Str::limit($doc->short_description, 60) }}</div>
                    </div>
                </label>
            @empty
                <p class="text-sm text-gray">Hujjat topilmadi.</p>
            @endforelse
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeModal('relatedModal')">Tasdiqlash</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const DOCUMENT_ID = {{ $document->id }};
const existingRecipients = @json($document->recipients->pluck('name', 'id'));
const selectedRecipients  = { ...existingRecipients };

document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
    updateRecipientsUI();
});

function loadTemplates() {
    fetch('{{ route('templates.index') }}', { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
        .then(r => r.json())
        .then(templates => {
            const container = document.getElementById('templatesList');
            container.innerHTML = templates.map(t => `
                <div onclick="selectTemplate(${t.id})"
                     style="padding:14px;border:1.5px solid var(--gray-200);border-radius:8px;cursor:pointer;transition:all .15s;"
                     onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-lt)'"
                     onmouseout="this.style.borderColor='var(--gray-200)';this.style.background=''">
                    <div style="font-size:14px;font-weight:700;color:var(--gray-800);">${t.name}</div>
                    ${t.description ? `<div style="font-size:12px;color:var(--gray-500);margin-top:4px;">${t.description}</div>` : ''}
                </div>
            `).join('');
        });
}

function selectTemplate(id) {
    fetch(`/templates/${id}/select`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ document_id: DOCUMENT_ID }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('mainFileName').textContent = data.file_name;
            closeModal('templateModal');
        }
    });
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('attachDropZone').style.borderColor = '#d1d5db';
    if (e.dataTransfer.files[0]) handleAttachFile(e.dataTransfer.files[0]);
}

function handleAttachFile(file) {
    if (!file) return;
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', CSRF_TOKEN);

    fetch(`/documents/${DOCUMENT_ID}/attachments`, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('newAttachList');
                const div  = document.createElement('div');
                div.className = 'file-card';
                div.style.marginTop = '8px';
                div.id = 'att-' + data.id;
                div.innerHTML = `
                    <div class="file-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#1a56db"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></div>
                    <div class="file-info"><div class="file-name">${data.original_name}</div><div class="file-meta">ZIP arxiv</div></div>
                    <div class="file-actions"><button type="button" class="btn btn-danger btn-sm" onclick="deleteAttachment(${data.id})">O'chirish</button></div>
                `;
                list.appendChild(div);
                closeModal('attachModal');
            }
        });
}

function deleteAttachment(id) {
    if (!confirm('Ilovani o\'chirishni tasdiqlaysizmi?')) return;

    fetch(`/documents/${DOCUMENT_ID}/attachments/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const el = document.getElementById('att-' + id);
            if (el) el.remove();
        }
    });
}

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

    const summary = document.getElementById('recipientsSummary');
    const items   = document.getElementById('recipientsItems');

    if (!count) { summary.style.display = 'none'; return; }
    summary.style.display = '';
    items.innerHTML = Object.entries(selectedRecipients).map(([id, name]) =>
        `<div style="font-size:13px;padding:5px 0;border-bottom:1px solid #f9fafb;">${name}</div>`
    ).join('');

    document.getElementById('recipientModalCount').textContent = count + ' ta tashkilot tanlangan';
}

function confirmRecipients() {
    closeModal('recipientModal');
}
</script>

@include('documents._org_tree_script')
@endpush
