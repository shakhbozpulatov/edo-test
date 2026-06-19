@extends('layouts.app')

@section('title', 'Mening hujjatlarim')
@section('page-title', 'Mening hujjatlarim')

@section('topbar-actions')
    <div style="display:flex;gap:8px;align-items:center;">
        <select class="form-control" style="width:auto;font-size:13px;" onchange="location.href='?status='+this.value">
            <option value="">Barcha holat</option>
            <option value="draft"  {{ request('status') == 'draft'  ? 'selected' : '' }}>Qoralamalar</option>
            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }}>Imzolangan</option>
        </select>
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Yangi hujjat
        </a>
    </div>
@endsection

@push('styles')
<style>
    .doc-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 16px;
        height: calc(100vh - var(--topbar-h) - 48px);
    }

    /* List panel */
    .doc-list {
        background: #fff;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }

    .doc-list-header {
        padding: 14px 16px;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--gray-50);
        flex-shrink: 0;
    }

    .doc-list-title { font-size: 12px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .07em; }
    .doc-count { background: var(--gray-200); color: var(--gray-600); font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 10px; }

    .doc-list-body { overflow-y: auto; flex: 1; }

    .doc-item {
        padding: 14px 16px;
        border-bottom: 1px solid var(--gray-100);
        cursor: pointer;
        transition: background .12s;
        text-decoration: none;
        display: block;
        position: relative;
    }

    .doc-item:hover { background: var(--gray-50); }

    .doc-item.active {
        background: var(--primary-lt);
        border-left: 3px solid var(--primary);
        padding-left: 13px;
    }

    .doc-item-number {
        font-size: 13px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .doc-item-desc {
        font-size: 12px;
        color: var(--gray-500);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 6px;
    }

    .doc-item-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .doc-date { font-size: 11px; color: var(--gray-400); }

    /* Detail panel */
    .doc-panel {
        background: #fff;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }

    .doc-panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        background: var(--gray-50);
        flex-shrink: 0;
    }

    .doc-panel-title { font-size: 18px; font-weight: 800; color: var(--gray-900); margin-bottom: 6px; }
    .doc-panel-body  { overflow-y: auto; flex: 1; padding: 0; }

    .detail-section { padding: 20px 24px; border-bottom: 1px solid var(--gray-100); }
    .detail-section-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-row {
        display: flex;
        gap: 0;
        padding: 8px 0;
        border-bottom: 1px solid var(--gray-50);
    }

    .detail-row:last-child { border-bottom: none; }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-400);
        width: 160px;
        flex-shrink: 0;
        padding-top: 1px;
    }

    .detail-value {
        font-size: 13.5px;
        color: var(--gray-800);
        flex: 1;
        line-height: 1.5;
    }

    /* Empty panel */
    .empty-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 40px;
        text-align: center;
    }

    .empty-panel-icon {
        width: 72px;
        height: 72px;
        background: var(--gray-100);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .empty-panel-icon svg { width: 36px; height: 36px; color: var(--gray-400); }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 500;
        background: var(--gray-100);
        color: var(--gray-700);
        margin: 2px;
        border: 1px solid var(--gray-200);
    }

    .qr-container {
        background: var(--gray-50);
        border: 1.5px dashed var(--gray-300);
        border-radius: var(--radius-sm);
        padding: 16px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .panel-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    /* Signed stat bar */
    .signed-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--green-lt);
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
        color: #065f46;
        margin-bottom: 8px;
    }

    .signed-bar svg { width: 16px; height: 16px; }
</style>
@endpush

@section('content')
<div class="doc-layout">
    <!-- LEFT LIST -->
    <div class="doc-list">
        <div class="doc-list-header">
            <span class="doc-list-title">Hujjatlar</span>
            <span class="doc-count">{{ $documents->count() }}</span>
        </div>
        <div class="doc-list-body">
            @forelse($documents as $doc)
                @php
                    $params = ['doc' => $doc->id];
                    if (request('status')) $params['status'] = request('status');
                @endphp
                <a href="{{ route('documents.index', $params) }}"
                   class="doc-item {{ $selected?->id == $doc->id ? 'active' : '' }}">
                    <div class="doc-item-number">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--gray-400)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ $doc->document_number ?: 'Raqamsiz hujjat' }}
                    </div>
                    <div class="doc-item-desc">{{ $doc->short_description ?: 'Tavsif yo\'q' }}</div>
                    <div class="doc-item-meta">
                        <span class="badge {{ $doc->status->badgeClass() }}">{{ $doc->status->label() }}</span>
                        <span class="doc-date">{{ $doc->created_at->format('d.m.Y') }}</span>
                    </div>
                </a>
            @empty
                <div style="padding:48px 24px;text-align:center;">
                    <div style="width:56px;height:56px;background:var(--gray-100);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="var(--gray-400)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div style="font-size:14px;font-weight:600;color:var(--gray-600);margin-bottom:6px;">Hujjat topilmadi</div>
                    <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Yangi hujjat
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- RIGHT DETAIL -->
    <div class="doc-panel">
        @if($selected)
            <div class="doc-panel-header">
                <div style="flex:1;min-width:0;">
                    <div class="doc-panel-title">{{ $selected->document_number ?: 'Raqamsiz hujjat' }}</div>
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span class="badge {{ $selected->status->badgeClass() }}">{{ $selected->status->label() }}</span>
                        <span style="font-size:12px;color:var(--gray-400);">
                            {{ $selected->created_at->format('d.m.Y H:i') }}
                        </span>
                        @if($selected->journal)
                            <span style="font-size:12px;color:var(--gray-400);">· {{ $selected->journal->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="panel-actions">
                    @if($selected->isDraft())
                        <a href="{{ route('documents.edit', $selected) }}" class="btn btn-secondary btn-sm">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Tahrirlash
                        </a>
                        <form method="POST" action="{{ route('documents.sign', $selected) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Hujjatni imzolashni tasdiqlaysizmi?')">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Imzolash
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('documents.destroy', $selected) }}"
                          onsubmit="return confirm('Hujjatni o\'chirishni tasdiqlaysizmi?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            O'chirish
                        </button>
                    </form>
                </div>
            </div>

            <div class="doc-panel-body">
                <!-- Main info -->
                <div class="detail-section">
                    <div class="detail-section-title">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Asosiy ma'lumotlar
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Jurnal</div>
                        <div class="detail-value">{{ $selected->journal?->name ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Hujjat raqami</div>
                        <div class="detail-value">{{ $selected->document_number ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Qisqa tavsif</div>
                        <div class="detail-value">{{ $selected->short_description ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Holat</div>
                        <div class="detail-value"><span class="badge {{ $selected->status->badgeClass() }}">{{ $selected->status->label() }}</span></div>
                    </div>
                    @if($selected->signed_at)
                        <div class="detail-row">
                            <div class="detail-label">Imzolangan</div>
                            <div class="detail-value">{{ $selected->signed_at->format('d.m.Y H:i') }}</div>
                        </div>
                    @endif
                </div>

                <!-- Files -->
                <div class="detail-section">
                    <div class="detail-section-title">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Fayllar
                    </div>

                    @if($selected->main_file_path)
                        <div class="file-card" style="margin-bottom:10px;">
                            <div class="file-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="file-info">
                                <div class="file-name">{{ $selected->main_file_name }}</div>
                                <div class="file-meta">Asosiy hujjat · DOCX</div>
                            </div>
                            <div class="file-actions">
                                <a href="{{ route('documents.download-main', $selected) }}" class="btn btn-secondary btn-sm">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Ochish
                                </a>
                            </div>
                        </div>
                    @else
                        <div style="font-size:13px;color:var(--gray-400);padding:8px 0;">Asosiy fayl biriktirilmagan</div>
                    @endif

                    @foreach($selected->attachments as $att)
                        <div class="file-card" style="margin-bottom:8px;">
                            <div class="file-icon" style="background:#fef3c7;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </div>
                            <div class="file-info">
                                <div class="file-name">{{ $att->original_name }}</div>
                                <div class="file-meta">Ilova · ZIP arxiv</div>
                            </div>
                            <div class="file-actions">
                                <a href="{{ route('attachments.download', [$selected, $att]) }}" class="btn btn-secondary btn-sm">Yuklab olish</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($selected->recipients->isNotEmpty())
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Qabul qiluvchilar ({{ $selected->recipients->count() }})
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                            @foreach($selected->recipients as $rec)
                                <span class="tag">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $rec->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($selected->relatedDocuments->isNotEmpty())
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            Aloqador hujjatlar
                        </div>
                        @foreach($selected->relatedDocuments as $rel)
                            <div style="font-size:13px;padding:5px 0;color:var(--gray-700);border-bottom:1px solid var(--gray-50);">
                                <span style="font-weight:600;">{{ $rel->document_number ?: 'Raqamsiz' }}</span>
                                @if($rel->short_description)
                                    <span style="color:var(--gray-500);"> — {{ Str::limit($rel->short_description, 50) }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- QR Code -->
                @if($selected->qr_base64)
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            QR Kod
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                            <div class="qr-container">
                                <img src="{{ $selected->qr_base64 }}" alt="QR Code"
                                     class="qr-img" id="qrImage" draggable="true"
                                     ondragstart="startQrDrag(event)"
                                     style="width:100px;height:100px;">
                                <div class="text-xs text-gray">Sudrab suring</div>
                            </div>
                            @if($selected->qr_position)
                                <div class="text-sm text-gray" style="padding-top:8px;">
                                    <div style="margin-bottom:4px;font-weight:600;color:var(--gray-600);">Joylashuv saqlangan:</div>
                                    <div>Sahifa: <strong>{{ $selected->qr_position['page'] }}</strong></div>
                                    <div>X: <strong>{{ $selected->qr_position['x'] }}</strong> px</div>
                                    <div>Y: <strong>{{ $selected->qr_position['y'] }}</strong> px</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- E-IMZO Stamp -->
                @if($selected->isSigned())
                    <div class="detail-section">
                        <div class="detail-section-title" style="color:#059669; font-weight: 700;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Raqamli Imzo Stamp
                        </div>
                        <div class="e-imzo-stamp" style="border: 2px dashed #10b981; border-radius: 8px; padding: 16px; display: inline-flex; align-items: center; gap: 16px; background: #f0fdf4; position: relative; overflow: hidden; max-width: 100%;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1.5px solid #10b981;">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 2px; text-align: left;">
                                <div style="font-size: 11px; font-weight: 800; color: #065f46; letter-spacing: 0.03em; text-transform: uppercase;">Elektron Raqamli Imzo Bilan Tasdiqlangan</div>
                                <div style="font-size: 12px; color: #047857; font-weight: 600;">F.I.Sh: {{ $selected->user->name }}</div>
                                <div style="font-size: 11px; color: #059669;">Lavozimi: {{ $selected->user->position ?? 'Bosh mutaxassis' }}</div>
                                <div style="font-size: 11px; color: #059669;">Tashkilot: {{ $selected->user->organization ?? 'Davlat boshqaruvi' }}</div>
                                <div style="font-size: 10px; color: #6b7280; font-family: monospace; margin-top: 2px;">Sertifikat: EDO-{{ strtoupper(md5($selected->id . $selected->user_id)) }}</div>
                                <div style="font-size: 10px; color: #6b7280;">Vaqti: {{ $selected->signed_at?->format('d.m.Y H:i:s') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="empty-panel">
                <div class="empty-panel-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div style="font-size:16px;font-weight:700;color:var(--gray-700);margin-bottom:6px;">Hujjat tanlanmagan</div>
                <div style="font-size:13px;color:var(--gray-400);max-width:240px;">Tafsilotlarni ko'rish uchun chap tomondagi ro'yxatdan birorta hujjat tanlang</div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function startQrDrag(event) {
    event.dataTransfer.setData('text/plain', 'qr-code');
}

document.addEventListener('dragover', e => e.preventDefault());
document.addEventListener('drop', function(e) {
    if (e.dataTransfer.getData('text/plain') !== 'qr-code') return;
    e.preventDefault();

    @if($selected)
    fetch('{{ route('documents.qr-position', $selected) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ qr_position: { page: 1, x: Math.round(e.clientX), y: Math.round(e.clientY) } }),
    });
    @endif
});
</script>
@endpush
