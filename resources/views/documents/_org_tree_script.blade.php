<script>
// ─── ORG TREE MODULE ─────────────────────────────────────────────────────────
let orgTreeLoaded = false;

document.addEventListener('DOMContentLoaded', function () {
    loadRegions();
});

function loadRegions() {
    fetch('{{ route('organizations.regions') }}')
        .then(r => r.json())
        .then(regions => {
            const sel = document.getElementById('orgRegion');
            if (!sel) return;
            regions.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.name;
                sel.appendChild(opt);
            });
        });
}

function loadDistricts() {
    const regionId = document.getElementById('orgRegion')?.value;
    const sel = document.getElementById('orgDistrict');
    if (!sel) return;
    sel.innerHTML = '<option value="">— Tuman / Shahar —</option>';
    if (!regionId) return;

    fetch(`/organizations/regions/${regionId}/districts`)
        .then(r => r.json())
        .then(districts => {
            districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id; opt.textContent = d.name;
                sel.appendChild(opt);
            });
        });
}

// Called when modal is opened
function loadOrgTree() {
    const container = document.getElementById('orgTree');
    if (!container) return;

    container.innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray-400);">Yuklanmoqda...</div>';

    const params = new URLSearchParams({
        region_id:   document.getElementById('orgRegion')?.value   || '',
        district_id: document.getElementById('orgDistrict')?.value || '',
        search:      document.getElementById('orgSearch')?.value   || '',
    });

    fetch(`{{ route('organizations.tree') }}?${params}`)
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(tree => renderTree(tree))
        .catch(err => {
            container.innerHTML = `<div style="text-align:center;padding:40px;color:var(--red);">Xatolik: ${err.message}</div>`;
        });
}

function filterOrgs() {
    clearTimeout(window._orgFilterTimer);
    window._orgFilterTimer = setTimeout(loadOrgTree, 280);
}

// ─── RENDER ──────────────────────────────────────────────────────────────────
function renderTree(nodes) {
    const container = document.getElementById('orgTree');
    if (!nodes || !nodes.length) {
        container.innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray-400);">Tashkilot topilmadi.</div>';
        return;
    }
    container.innerHTML = nodes.map(n => renderNode(n, 0)).join('');
}

function renderNode(node, depth) {
    const hasChildren = node.children && node.children.length > 0;
    const isChecked   = (typeof selectedRecipients !== 'undefined' && selectedRecipients[node.id]) ? 'checked' : '';
    const safeName    = node.name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");

    const indent = depth * 20;

    if (node.is_category) {
        // Category row: toggle + "select all" checkbox + label
        const childrenHtml = hasChildren
            ? node.children.map(c => renderNode(c, depth + 1)).join('')
            : '';

        return `
        <div class="tree-node">
            <div class="tree-node-row tree-category" style="padding-left:${indent + 4}px;"
                 onclick="toggleCategory(this)">
                <span class="tree-toggle">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <input type="checkbox" id="cat-${node.id}" value="${node.id}"
                       onclick="event.stopPropagation(); toggleCategoryCheck(this, '${safeName}')"
                       style="width:auto;cursor:pointer;flex-shrink:0;"
                       ${isChecked}>
                <label for="cat-${node.id}" onclick="event.stopPropagation();" style="cursor:pointer;flex:1;font-weight:700;color:var(--primary);">
                    ${node.name}
                    ${hasChildren ? `<span style="font-size:10px;color:var(--gray-400);font-weight:400;margin-left:4px;">(${node.children.length} ta)</span>` : ''}
                </label>
            </div>
            <div class="tree-children">${childrenHtml}</div>
        </div>`;
    }

    // Leaf node (actual organization)
    return `
    <div class="tree-node">
        <div class="tree-node-row" style="padding-left:${indent + 8}px;">
            <span class="tree-toggle-placeholder"></span>
            <input type="checkbox" id="org-${node.id}" value="${node.id}"
                   onchange="syncRecipient(this, '${safeName}')"
                   style="width:auto;cursor:pointer;flex-shrink:0;"
                   ${isChecked}>
            <label for="org-${node.id}" style="cursor:pointer;flex:1;">
                ${node.name}
            </label>
        </div>
    </div>`;
}

function toggleCategory(rowEl) {
    const childrenEl = rowEl.parentElement.querySelector('.tree-children');
    if (!childrenEl) return;
    const isOpen = childrenEl.classList.toggle('open');
    const toggle = rowEl.querySelector('.tree-toggle');
    if (toggle) toggle.classList.toggle('open', isOpen);
}

function toggleCategoryCheck(checkbox, catName) {
    const childCheckboxes = checkbox.closest('.tree-node')
        .querySelectorAll('.tree-children input[type="checkbox"]');

    childCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        const label = cb.parentElement.querySelector('label');
        const name  = label ? label.textContent.trim() : cb.value;
        if (checkbox.checked) {
            selectedRecipients[cb.value] = name;
        } else {
            delete selectedRecipients[cb.value];
        }
    });

    if (checkbox.checked) {
        selectedRecipients[checkbox.value] = catName;
    } else {
        delete selectedRecipients[checkbox.value];
    }

    updateRecipientsUI();
}

// ─── CONFIRM & INJECT INTO FORM ──────────────────────────────────────────────
function confirmRecipients() {
    // Inject hidden inputs into the form
    const container = document.getElementById('recipientInputs');
    if (container) {
        container.innerHTML = Object.keys(selectedRecipients)
            .map(id => `<input type="hidden" name="recipient_ids[]" value="${id}">`)
            .join('');
    }

    updateRecipientsUI();

    const countEl = document.getElementById('recipientModalCount');
    if (countEl) {
        countEl.textContent = Object.keys(selectedRecipients).length + ' ta tashkilot tanlangan';
    }

    closeModal('recipientModal');
}
</script>
