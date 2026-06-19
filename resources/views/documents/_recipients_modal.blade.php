<div class="modal-overlay" id="recipientModal">
    <div class="modal modal-lg">
        <div class="modal-header" style="background: var(--gray-50); border-bottom: 1.5px solid var(--gray-200);">
            <div style="display:flex; align-items:center; gap:10px;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.02em;">Qabul qiluvchi davlat organlari va tashkilotlari</h3>
            </div>
            <button class="modal-close" onclick="closeModal('recipientModal')">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-body" style="padding-bottom:0; background: #fff;">
            {{-- Filters --}}
            <div style="display:grid;grid-template-columns:1fr auto auto;gap:12px;margin-bottom:16px;">
                <input type="text" id="orgSearch" class="form-control" placeholder="Tashkilot nomini kiriting..."
                       oninput="filterOrgs()" style="font-size:13px; height: 38px; border-radius: 6px;">
                <select id="orgRegion" class="form-control" style="min-width:170px; font-size:13px; height: 38px; border-radius: 6px;" onchange="loadDistricts(); filterOrgs()">
                    <option value="">— Hudud (Viloyat) —</option>
                </select>
                <select id="orgDistrict" class="form-control" style="min-width:170px; font-size:13px; height: 38px; border-radius: 6px;" onchange="filterOrgs()">
                    <option value="">— Tuman / Shahar —</option>
                </select>
            </div>

            <div id="orgTree" style="min-height:300px; max-height:420px; overflow-y:auto; border: 1.5px solid var(--gray-200); border-radius: var(--radius-sm); padding: 12px; background: var(--gray-50);">
                <div class="text-gray text-sm" style="text-align:center;padding:40px;">Yuklanmoqda...</div>
            </div>
        </div>

        <div class="modal-footer" style="background: var(--gray-50); border-top: 1.5px solid var(--gray-200); padding: 14px 24px;">
            <div id="recipientModalCount" class="text-sm font-semibold" style="margin-right:auto; color: var(--primary);">0 ta tashkilot tanlangan</div>
            <button class="btn btn-secondary" onclick="closeModal('recipientModal')" style="border-radius: 6px;">Bekor qilish</button>
            <button class="btn btn-primary" onclick="confirmRecipients()" style="border-radius: 6px; background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 100%);">Tasdiqlash</button>
        </div>
    </div>
</div>
