const API_URL = 'backend/api/api.php';
const state = { podcasts: [], themes: [] };

async function requestApi(resource, options = {}, id = null) {
    const query = new URLSearchParams({ resource });
    if (id !== null) query.set('id', id);

    const response = await fetch(`${API_URL}?${query.toString()}`, {
        ...options,
        headers: { 'Content-Type': 'application/json', ...(options.headers || {}) }
    });
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data.erreur || data.error || 'La requête a échoué.');
    }
    return data;
}

function toast(message, type = 'success') {
    const container = document.querySelector('#toast-container');
    const item = document.createElement('div');
    item.className = `rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg ${type === 'error' ? 'bg-red-700' : 'bg-emerald-700'}`;
    item.textContent = message;
    container.appendChild(item);
    window.setTimeout(() => item.remove(), 3500);
}

function setLoading(button, isLoading, label = 'En cours...') {
    if (isLoading) {
        button.dataset.label = button.textContent;
        button.disabled = true;
        button.classList.add('cursor-wait', 'opacity-75');
        button.innerHTML = `<span class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-r-transparent align-[-3px]"></span>${label}`;
        return;
    }

    button.textContent = button.dataset.label || 'Enregistrer';
    delete button.dataset.label;
    button.disabled = false;
    button.classList.remove('cursor-wait', 'opacity-75');
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    })[character]);
}

function afficherThematiques() {
    const list = document.querySelector('#theme-list');
    const select = document.querySelector('#podcast-theme');
    list.replaceChildren();

    if (state.themes.length === 0) {
        const empty = document.createElement('li');
        empty.className = 'rounded-lg border border-dashed border-slate-300 p-5 text-sm text-slate-500 sm:col-span-2 lg:col-span-3';
        empty.textContent = 'Aucune thématique. Ajoutez-en une pour classer vos podcasts.';
        list.appendChild(empty);
    } else {
        state.themes.forEach((theme) => {
            const item = document.createElement('li');
            item.className = 'flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3';
            item.innerHTML = '<span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>';
            const name = document.createElement('span');
            name.className = 'font-medium text-slate-800';
            name.textContent = theme.name;
            item.appendChild(name);
            list.appendChild(item);
        });
    }

    const selected = select.value;
    select.innerHTML = '<option value="">Choisir une thématique</option>' + state.themes.map((theme) =>
        `<option value="${Number(theme.id)}">${escapeHtml(theme.name)}</option>`
    ).join('');
    select.value = selected;
}

function afficherPodcasts() {
    const list = document.querySelector('#podcast-list');
    const summary = document.querySelector('#podcast-summary');
    list.replaceChildren();
    summary.textContent = `${state.podcasts.length} podcast${state.podcasts.length === 1 ? '' : 's'}`;

    if (state.podcasts.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 sm:col-span-2 xl:col-span-3';
        empty.textContent = 'Aucun podcast pour le moment. Ajoutez votre premier épisode.';
        list.appendChild(empty);
        return;
    }

    state.podcasts.forEach((podcast) => {
        const card = document.createElement('article');
        card.className = 'flex min-h-56 flex-col rounded-xl border border-slate-200 bg-white p-5';
        const audioLink = podcast.audio_url
            ? `<a class="text-sm font-semibold text-indigo-700 hover:underline" href="${escapeHtml(podcast.audio_url)}" target="_blank" rel="noopener noreferrer">Écouter l’épisode ↗</a>`
            : '<span class="text-sm text-slate-400">Aucun lien audio</span>';
        card.innerHTML = `
            <div class="mb-3 flex items-start justify-between gap-3">
                <h3 class="break-words text-lg font-semibold text-slate-950">${escapeHtml(podcast.title)}</h3>
                <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">${escapeHtml(podcast.theme_name)}</span>
            </div>
            <p class="mb-5 flex-1 whitespace-pre-line break-words text-sm leading-6 text-slate-600">${escapeHtml(podcast.description)}</p>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                ${audioLink}
                <div class="flex gap-3">
                    <button class="text-sm font-semibold text-slate-600 hover:text-indigo-700" data-action="edit" data-id="${Number(podcast.id)}" type="button">Modifier</button>
                    <button class="text-sm font-semibold text-red-600 hover:text-red-800" data-action="delete" data-id="${Number(podcast.id)}" type="button">Supprimer</button>
                </div>
            </div>`;
        list.appendChild(card);
    });
}

async function chargerDonnees() {
    try {
        const [podcasts, themes] = await Promise.all([
            requestApi('podcasts'),
            requestApi('themes')
        ]);
        state.podcasts = podcasts;
        state.themes = themes;
        afficherThematiques();
        afficherPodcasts();
    } catch (error) {
        toast(error.message, 'error');
        document.querySelector('#podcast-list').textContent = 'Impossible de charger les podcasts.';
        document.querySelector('#theme-list').textContent = 'Impossible de charger les thématiques.';
    }
}

function afficherVue() {
    const themesActifs = window.location.hash === '#themes';
    document.querySelector('#section-podcasts').classList.toggle('hidden', themesActifs);
    document.querySelector('#section-themes').classList.toggle('hidden', !themesActifs);
    document.querySelector('#page-title').textContent = themesActifs ? 'Gestion des thématiques' : 'Bibliothèque de podcasts';

    document.querySelector('#nav-podcasts').classList.toggle('bg-indigo-600', !themesActifs);
    document.querySelector('#nav-podcasts').classList.toggle('text-white', !themesActifs);
    document.querySelector('#nav-podcasts').classList.toggle('text-slate-300', themesActifs);
    document.querySelector('#nav-themes').classList.toggle('bg-indigo-600', themesActifs);
    document.querySelector('#nav-themes').classList.toggle('text-white', themesActifs);
    document.querySelector('#nav-themes').classList.toggle('text-slate-300', !themesActifs);
    document.querySelector('#nav-podcasts').toggleAttribute('aria-current', !themesActifs);
    document.querySelector('#nav-themes').toggleAttribute('aria-current', themesActifs);
}

function ouvrirFormulaire(podcast = null) {
    const section = document.querySelector('#section-form-podcast');
    const form = document.querySelector('#form-podcast');
    form.reset();
    document.querySelector('#podcast-id').value = podcast ? podcast.id : '';
    document.querySelector('#form-title').textContent = podcast ? 'Modifier le podcast' : 'Ajouter un podcast';
    document.querySelector('#btn-save-podcast').textContent = podcast ? 'Enregistrer les modifications' : 'Enregistrer';
    if (podcast) {
        document.querySelector('#podcast-title').value = podcast.title;
        document.querySelector('#podcast-description').value = podcast.description;
        document.querySelector('#podcast-audio').value = podcast.audio_url || '';
        document.querySelector('#podcast-theme').value = podcast.theme_id;
    }
    section.classList.remove('hidden');
    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    document.querySelector('#podcast-title').focus();
}

function fermerFormulaire() {
    document.querySelector('#section-form-podcast').classList.add('hidden');
    document.querySelector('#form-podcast').reset();
    document.querySelector('#podcast-id').value = '';
}

document.querySelector('#btn-show-form-podcast').addEventListener('click', () => ouvrirFormulaire());
document.querySelector('#btn-cancel-form-podcast').addEventListener('click', fermerFormulaire);
document.querySelectorAll('nav a').forEach((link) => link.addEventListener('click', afficherVue));
window.addEventListener('hashchange', afficherVue);

document.querySelector('#form-podcast').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const button = document.querySelector('#btn-save-podcast');
    const id = document.querySelector('#podcast-id').value;
    const payload = Object.fromEntries(new FormData(form).entries());
    const method = id ? 'PUT' : 'POST';
    setLoading(button, true);

    try {
        await requestApi('podcasts', { method, body: JSON.stringify(payload) }, id || null);
        fermerFormulaire();
        await chargerDonnees();
        toast(id ? 'Podcast modifié.' : 'Podcast ajouté.');
    } catch (error) {
        toast(error.message, 'error');
    } finally {
        setLoading(button, false);
    }
});

document.querySelector('#form-theme').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const button = document.querySelector('#btn-save-theme');
    const input = document.querySelector('#theme-name');
    setLoading(button, true, 'Ajout...');

    try {
        await requestApi('themes', { method: 'POST', body: JSON.stringify({ name: input.value }) });
        form.reset();
        await chargerDonnees();
        toast('Thématique ajoutée.');
    } catch (error) {
        toast(error.message, 'error');
    } finally {
        setLoading(button, false);
    }
});

document.querySelector('#podcast-list').addEventListener('click', async (event) => {
    const button = event.target.closest('button[data-action]');
    if (!button) return;
    const podcast = state.podcasts.find((item) => Number(item.id) === Number(button.dataset.id));
    if (!podcast) return;

    if (button.dataset.action === 'edit') {
        ouvrirFormulaire(podcast);
        return;
    }
    if (!window.confirm(`Supprimer « ${podcast.title} » ?`)) return;

    button.disabled = true;
    button.textContent = 'Suppression...';
    try {
        await requestApi('podcasts', { method: 'DELETE' }, podcast.id);
        await chargerDonnees();
        toast('Podcast supprimé.');
    } catch (error) {
        toast(error.message, 'error');
        button.disabled = false;
        button.textContent = 'Supprimer';
    }
});

afficherVue();
chargerDonnees();