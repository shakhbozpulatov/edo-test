@extends('layouts.app')

@section('title', 'Imzolangan hujjatlar')
@section('page-title', 'Imzolangan hujjatlar')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/documents/signed.css') }}">
@endpush

@section('content')
<div class="doc-layout">
    <div class="doc-list">
        <div class="doc-list-header">Imzolangan hujjatlar ({{ $documents->count() }})</div>
        <div class="doc-list-body">
            @forelse($documents as $doc)
                <a href="?doc={{ $doc->id }}" class="doc-item {{ $selected?->id == $doc->id ? 'active' : '' }}">
                    <div class="doc-item-number">{{ $doc->document_number ?: 'Raqamsiz hujjat' }}</div>
                    <div class="doc-item-desc">{{ $doc->short_description ?: '—' }}</div>
                    <div class="doc-item-meta">
                        <span class="badge badge-success">Imzolangan</span>
                        <span style="font-size:11px;color:#9ca3af;">{{ $doc->signed_at?->format('d.m.Y') }}</span>
                    </div>
                </a>
            @empty
                <div style="padding:40px;text-align:center;color:#9ca3af;">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 10px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Imzolangan hujjat yo'q.
                </div>
            @endforelse
        </div>
    </div>

    <div class="doc-panel">
        @if($selected)
            <div class="doc-panel-header">
                <div>
                    <div style="font-size:16px;font-weight:700;">{{ $selected->document_number ?: 'Raqamsiz hujjat' }}</div>
                    <div style="margin-top:6px;">
                        <span class="signed-badge">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Imzolangan — {{ $selected->signed_at?->format('d.m.Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($selected->main_file_path)
                        <a href="{{ route('documents.download-main', $selected) }}" class="btn btn-secondary btn-sm">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Yuklab olish
                        </a>
                    @endif
                </div>
            </div>

            <div class="doc-panel-body">
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
                    <div class="detail-label">Asosiy fayl</div>
                    <div class="detail-value">
                        @if($selected->main_file_path)
                            <a href="{{ route('documents.download-main', $selected) }}" class="btn btn-secondary btn-sm">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ $selected->main_file_name }}
                            </a>
                        @else
                            <span class="text-gray">—</span>
                        @endif
                    </div>
                </div>

                @if($selected->attachments->isNotEmpty())
                    <div class="detail-row">
                        <div class="detail-label">Ilovalar</div>
                        <div class="detail-value">
                            @foreach($selected->attachments as $att)
                                <div style="margin-bottom:6px;">
                                    <a href="{{ route('attachments.download', [$selected, $att]) }}" class="btn btn-secondary btn-sm">
                                        {{ $att->original_name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($selected->recipients->isNotEmpty())
                    <div class="detail-row">
                        <div class="detail-label">Qabul qiluvchilar</div>
                        <div class="detail-value">
                            @foreach($selected->recipients as $rec)
                                <span class="tag">{{ $rec->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($selected->relatedDocuments->isNotEmpty())
                    <div class="detail-row">
                        <div class="detail-label">Aloqador hujjatlar</div>
                        <div class="detail-value">
                            @foreach($selected->relatedDocuments as $rel)
                                <div style="font-size:13px;margin-bottom:4px;">{{ $rel->document_number ?: 'Raqamsiz' }} — {{ Str::limit($rel->short_description, 50) }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Holat</div>
                    <div class="detail-value"><span class="badge badge-success">Imzolangan</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Imzolangan vaqt</div>
                    <div class="detail-value">{{ $selected->signed_at?->format('d.m.Y H:i') }}</div>
                </div>

                @if($selected->qr_base64)
                    <div style="margin-top:20px;">
                        <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">QR Kod</div>
                        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px;display:inline-block;">
                            <img src="{{ $selected->qr_base64 }}" alt="QR Code" style="width:120px;height:120px;display:block;">
                        </div>
                    </div>
                @endif

                <!-- E-IMZO Stamp -->
                <div style="margin-top:24px; border: 2px dashed #10b981; border-radius: 8px; padding: 16px; display: inline-flex; align-items: center; gap: 16px; background: #f0fdf4; position: relative; overflow: hidden; max-width: 100%;">
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
        @else
            <div class="empty-panel">
                <svg fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div style="font-size:15px;font-weight:600;color:#6b7280;">Hujjat tanlanmagan</div>
                <div class="text-sm text-gray mt-1">Ko'rish uchun chap tomondagi ro'yxatdan hujjat tanlang</div>
            </div>
        @endif
    </div>
</div>
@endsection
