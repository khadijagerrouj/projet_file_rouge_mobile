const form = document.getElementById('form-{{entityLower}}');
const sectionForm = document.getElementById('section-form-{{entityLower}}');
const buttonAdd = document.getElementById('btn-show-form-{{entityLower}}');
const buttonCancel = document.getElementById('btn-cancel-form-{{entityLower}}');
const tableBody = document.getElementById('table-{{entitiesLower}}-body');
const buttonSave = document.getElementById('enregistrer-{{entityLower}}');
const entityId = document.getElementById('{{entityLower}}-id');
const api = 'backend/api/{{apiEndpoint}}.php';
const itemsById = new Map();

function Loading(isLoading) {
    if (isLoading) {
        buttonSave.dataset.label = buttonSave.textContent;
        buttonSave.disabled = true;
        buttonSave.textContent = 'En cours...';
    } else {
        buttonSave.disabled = false;
        buttonSave.textContent = buttonSave.dataset.label || 'Enregistrer';
        delete buttonSave.dataset.label;
    }
}

function escapeHTML(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    })[character]);
}

function Toast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `${type === 'success' ? 'bg-green-600' : 'bg-red-600'} rounded-lg px-4 py-3 text-white shadow`;
    toast.textContent = message;
    container.appendChild(toast);
    window.setTimeout(() => toast.remove(), 3000);
}

async function Afficher() {
    try {
        const response = await fetch(api);
        if (!response.ok) throw new Error('Erreur de chargement.');
        const items = await response.json();
        tableBody.replaceChildren();
        itemsById.clear();
        items.forEach((item) => {
            itemsById.set(String(item.id), item);
            const row = document.createElement('tr');
            row.innerHTML = `<td class="p-4 text-sm text-slate-700">${escapeHTML(item.id)}</td>{{tableRowColumns}}
                <td class="p-4 text-sm"><button type="button" data-action="edit" data-id="${escapeHTML(item.id)}" class="mr-3 font-medium text-indigo-700">Modifier</button><button type="button" data-action="delete" data-id="${escapeHTML(item.id)}" class="font-medium text-red-700">Supprimer</button></td>`;
            tableBody.appendChild(row);
        });
    } catch (error) {
        Toast(error.message, 'error');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    Afficher();
    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const id = entityId.value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${api}?id=${encodeURIComponent(id)}` : api;
        Loading(true);
        try {
            const payload = { {{formPayloadMappings}} };
            const response = await fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.erreur || 'Échec de l’enregistrement.');
            Toast(id ? 'Modification enregistrée.' : 'Ajout effectué.');
            form.reset();
            entityId.value = '';
            sectionForm.classList.add('hidden');
            await Afficher();
        } catch (error) {
            Toast(error.message, 'error');
        } finally {
            Loading(false);
        }
    });

    tableBody?.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) return;
        const item = itemsById.get(button.dataset.id);
        if (!item) return;

        if (button.dataset.action === 'edit') {
            entityId.value = item.id;
            {{editFormMappings}}
            sectionForm.classList.remove('hidden');
            sectionForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

        if (!window.confirm('Supprimer cet élément ?')) return;
        button.disabled = true;
        try {
            const response = await fetch(`${api}?id=${encodeURIComponent(item.id)}`, { method: 'DELETE' });
            const result = await response.json();
            if (!response.ok) throw new Error(result.erreur || 'Échec de la suppression.');
            Toast(result.message || 'Suppression effectuée.');
            await Afficher();
        } catch (error) {
            button.disabled = false;
            Toast(error.message, 'error');
        }
    });
});

buttonAdd?.addEventListener('click', () => {
    form?.reset();
    if (entityId) entityId.value = '';
    sectionForm.classList.remove('hidden');
});
buttonCancel?.addEventListener('click', () => {
    form?.reset();
    if (entityId) entityId.value = '';
    sectionForm.classList.add('hidden');
});