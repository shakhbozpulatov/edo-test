<script>
// Organisation tree logic (shared between create and edit views)
let orgTreeData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadRegions();
});

document.getElementById('recipientModal')?.addEventListener('transitionend', function() {});

// Open modal hook
const _origOpenModal = window.openModal;
window.openModal = function(id) {
    _origOpenModal(id);
    if (id === 'recipientModal') {
        loadOrgTree();
    }
};

function loadRegions() {
    fetch('{{ route('organizations.regions') }}')
        .then(r => r.json())
        .then(regions => {
            const sel = document.getElementById('orgRegion');
            regions.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.name;
                sel.appendChild(opt);
            });
        });
}

function loadDistricts() {
    const regionId = document.getElementById('orgRegion').value;
    const sel = document.getElementById('orgDistrict');
    sel.innerHTML = '<option value="">— Tuman —</option>';

    if (!regionId) return;

    fetch(`/organizations/regions/${regionId}/districts`)
        .then(r => r.json())
        .then(districts => {
            districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.name;
                sel.appendChild(opt);
            });
        });
}

function loadOrgTree() {
    const params = new URLSearchParams({
        region_id:   document.getElementById('orgRegion')?.value   || '',
        district_id: document.getElementById('orgDistrict')?.value || '',
        search:      document.getElementById('orgSearch')?.value   || '',
        selected:    Object.keys(typeof selectedRecipients !== 'undefined' ? selectedRecipients : {}).join(','),
    });

    fetch(`{{ route('organizations.tree') }}?${params}`)
        .then(r => r.json())
        .then(tree => {
            orgTreeData = tree;
            renderTree(tree);
        });
}

function filterOrgs() {
    loadOrgTree();
}

function renderTree(nodes) {
    const container = document.getElementById('orgTree');
    if (!nodes.length) {
        container.innerHTML = '<div class="text-gray text-sm" style="text-align:center;padding:32px;">Tashkilot topilmadi.</div>';
        return;
    }
    container.innerHTML = nodes.map(n => renderNode(n)).join('');
}

function renderNode(node) {
    const hasChildren = node.children && node.children.length > 0;
    const isChecked   = node.checked ? 'checked' : '';
    const catClass    = node.is_category ? 'tree-category' : '';

    const toggleBtn = hasChildren
        ? `<span class="tree-toggle" onclick="toggleNode(this)">&#9654;</span>`
        : `<span class="tree-toggle-placeholder"></span>`;

    const children = hasChildren
        ? `<div class="tree-children">${node.children.map(c => renderNode(c)).join('')}</div>`
        : '';

    return `
        <div class="tree-node">
            <div class="tree-node-row ${catClass}">
                ${toggleBtn}
                <input type="checkbox" name="recipient_ids[]" value="${node.id}" ${isChecked}
                       id="org-${node.id}" style="width:auto;cursor:pointer;"
                       onchange="syncRecipient(this, '${node.name.replace(/'/g,"\\'")}')">
                <label for="org-${node.id}" style="cursor:pointer;flex:1;">
                    ${node.name}
                </label>
            </div>
            ${children}
        </div>
    `;
}

function toggleNode(btn) {
    const childrenEl = btn.closest('.tree-node-row').nextElementSibling;
    if (!childrenEl) return;
    const open = childrenEl.classList.toggle('open');
    btn.classList.toggle('open', open);
}

function confirmRecipients() {
    const count = Object.keys(typeof selectedRecipients !== 'undefined' ? selectedRecipients : {}).length;
    const countEl = document.getElementById('recipientModalCount');
    if (countEl) countEl.textContent = count + ' ta tashkilot tanlangan';
    closeModal('recipientModal');
}
</script>
