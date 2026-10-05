const API_URL = '../backend/api.php';

async function appelerApi(url, options = {}) {
    const reponse = await fetch(url, options);
    const resultat = await reponse.json();

    if (!reponse.ok) {
        throw new Error(resultat.message || 'Une erreur est survenue.');
    }

    return resultat;
}

function optionsJson(methode, donnees) {
    return {
        method: methode,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(donnees),
    };
}

function formaterDate(date) {
    if (!date) return '';

    return new Intl.DateTimeFormat('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(`${date}T12:00:00`));
}

function creerCarte(podcast) {
    const carte = document.createElement('article');
    carte.className = 'overflow-hidden rounded-lg border border-[#e0e5dc] bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md';

    const entete = document.createElement('div');
    entete.className = 'mb-4 flex items-center justify-between';
    const badge = document.createElement('span');
    badge.className = 'rounded-full bg-[#e8eee5] px-2.5 py-1 text-[11px] font-bold text-[#526e61]';
    badge.textContent = podcast.thematique || 'Sans thématique';
    const onde = document.createElement('span');
    onde.className = 'flex h-6 items-center gap-[3px] text-[#d36349]';
    onde.setAttribute('aria-hidden', 'true');
    [8, 15, 10, 20, 12, 17, 7, 14, 19, 9, 15, 6].forEach((hauteur, index) => {
        const barre = document.createElement('i');
        barre.className = 'w-[2px] rounded-full bg-current';
        barre.style.height = `${hauteur}px`;
        barre.style.opacity = String(0.45 + (index % 3) * 0.2);
        onde.append(barre);
    });
    entete.append(badge, onde);

    const titre = document.createElement('h3');
    titre.className = 'font-display text-2xl leading-snug text-[#173e36]';
    titre.textContent = podcast.titre;

    const description = document.createElement('p');
    description.className = 'mt-2 min-h-[3rem] text-sm leading-6 text-[#687972]';
    description.textContent = podcast.description || 'Aucune description pour le moment.';

    const bas = document.createElement('div');
    bas.className = 'mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#edf0e9] pt-4';
    const date = document.createElement('time');
    date.className = 'text-xs text-[#87938b]';
    date.textContent = formaterDate(podcast.date);
    const actions = document.createElement('div');
    actions.className = 'flex items-center gap-3';

    if (podcast.url) {
        const lien = document.createElement('a');
        lien.href = podcast.url;
        lien.target = '_blank';
        lien.rel = 'noopener noreferrer';
        lien.className = 'text-xs font-bold text-[#bd543d] hover:underline';
        lien.textContent = 'Écouter ↗';
        actions.append(lien);
    }

    const modifier = document.createElement('button');
    modifier.type = 'button';
    modifier.dataset.action = 'modifier';
    modifier.dataset.id = podcast.id;
    modifier.className = 'text-xs font-semibold text-[#526e61] hover:text-[#173e36] hover:underline';
    modifier.textContent = 'Modifier';

    const supprimer = document.createElement('button');
    supprimer.type = 'button';
    supprimer.dataset.action = 'supprimer';
    supprimer.dataset.id = podcast.id;
    supprimer.className = 'text-xs font-semibold text-[#b44d3a] hover:underline';
    supprimer.textContent = 'Supprimer';

    actions.append(modifier, supprimer);
    bas.append(date, actions);
    carte.append(entete, titre, description, bas);

    return carte;
}

function remplirSelecteur(selecteur, thematiques, valeurActuelle = '') {
    if (!selecteur) return;

    const libelleInitial = selecteur.id === 'thematique' ? 'Sans thématique' : 'Sans thématique';
    selecteur.replaceChildren(new Option(libelleInitial, ''));

    thematiques.forEach((thematique) => {
        const option = new Option(thematique.nom, thematique.nom);
        option.selected = thematique.nom === valeurActuelle;
        selecteur.add(option);
    });
}

function initialiserBibliotheque() {
    const liste = document.querySelector('#podcast-list');
    if (!liste) return;

    const chargement = document.querySelector('#loading-state');
    const compteur = document.querySelector('#podcast-count');
    const listeThematiques = document.querySelector('#theme-list');
    const filtres = document.querySelector('#theme-filters');
    const formulaireThematique = document.querySelector('#theme-form');
    const messageThematique = document.querySelector('#theme-message');
    const dialogue = document.querySelector('#edit-dialog');
    const formulaireEdition = document.querySelector('#edit-form');
    const selecteurEdition = document.querySelector('#edit-thematique');
    const recherche = document.querySelector('#podcast-search');
    let podcasts = [];
    let thematiques = [];
    let filtreActif = '';
    let rechercheActive = '';

    function afficherPodcasts() {
        const resultats = podcasts.filter((podcast) => {
            const correspondAuFiltre = !filtreActif || podcast.thematique === filtreActif;
            const texte = `${podcast.titre} ${podcast.description} ${podcast.thematique}`.toLocaleLowerCase('fr');
            return correspondAuFiltre && texte.includes(rechercheActive);
        });

        liste.replaceChildren();
        resultats.forEach((podcast) => liste.append(creerCarte(podcast)));
        compteur.textContent = `${podcasts.length} ${podcasts.length > 1 ? 'épisodes' : 'épisode'}`;
        chargement.classList.toggle('hidden', resultats.length > 0);

        if (podcasts.length === 0) {
            chargement.textContent = 'Votre bibliothèque est vide. Ajoutez votre premier podcast pour commencer.';
        } else if (resultats.length === 0) {
            chargement.textContent = 'Aucun podcast ne correspond à cette recherche.';
            chargement.classList.remove('hidden');
        }
    }

    function afficherThematiques() {
        listeThematiques.replaceChildren();
        filtres.replaceChildren();
        filtres.classList.toggle('hidden', thematiques.length === 0);
        filtres.classList.toggle('sm:flex', thematiques.length > 0);

        const boutonTout = document.createElement('button');
        boutonTout.type = 'button';
        boutonTout.dataset.theme = '';
        boutonTout.className = `shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold ${filtreActif === '' ? 'bg-[#173e36] text-white' : 'bg-white text-[#526e61] hover:bg-[#e8eee5]'}`;
        boutonTout.textContent = 'Tout';
        filtres.append(boutonTout);

        thematiques.forEach((thematique) => {
            const total = podcasts.filter((podcast) => podcast.thematique === thematique.nom).length;
            const ligne = document.createElement('li');
            const bouton = document.createElement('button');
            bouton.type = 'button';
            bouton.dataset.theme = thematique.nom;
            bouton.className = `flex w-full items-center justify-between rounded-md px-3 py-2.5 text-left text-sm transition ${filtreActif === thematique.nom ? 'bg-[#e8eee5] text-[#173e36]' : 'text-[#526e61] hover:bg-white'}`;
            const nom = document.createElement('span');
            nom.className = 'flex items-center gap-2.5 font-medium';
            const point = document.createElement('i');
            point.className = 'h-2 w-2 rounded-full bg-[#d36349]';
            const texte = document.createElement('span');
            texte.textContent = thematique.nom;
            const nombre = document.createElement('span');
            nombre.className = 'text-xs text-[#87938b]';
            nombre.textContent = String(total);
            nom.append(point, texte);
            bouton.append(nom, nombre);
            ligne.append(bouton);
            listeThematiques.append(ligne);

            const filtre = document.createElement('button');
            filtre.type = 'button';
            filtre.dataset.theme = thematique.nom;
            filtre.className = `shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold ${filtreActif === thematique.nom ? 'bg-[#173e36] text-white' : 'bg-white text-[#526e61] hover:bg-[#e8eee5]'}`;
            filtre.textContent = thematique.nom;
            filtres.append(filtre);
        });

        document.querySelector('#theme-count').textContent = String(thematiques.length);
        remplirSelecteur(selecteurEdition, thematiques);
    }

    async function chargerDonnees() {
        try {
            const resultat = await appelerApi(`${API_URL}?action=liste`);
            podcasts = resultat.podcasts || [];
            thematiques = resultat.thematiques || [];
            afficherThematiques();
            afficherPodcasts();
        } catch (erreur) {
            chargement.textContent = erreur.message;
            chargement.classList.remove('hidden');
        }
    }

    filtres.addEventListener('click', gererFiltre);
    listeThematiques.addEventListener('click', gererFiltre);

    function gererFiltre(evenement) {
        const bouton = evenement.target.closest('[data-theme]');
        if (!bouton) return;
        filtreActif = bouton.dataset.theme;
        afficherThematiques();
        afficherPodcasts();
    }

    recherche.addEventListener('input', () => {
        rechercheActive = recherche.value.trim().toLocaleLowerCase('fr');
        afficherPodcasts();
    });

    formulaireThematique.addEventListener('submit', async (evenement) => {
        evenement.preventDefault();
        messageThematique.textContent = '';

        try {
            await appelerApi(`${API_URL}?action=thematique`, optionsJson('POST', {
                nom: document.querySelector('#theme-name').value.trim(),
            }));
            formulaireThematique.reset();
            await chargerDonnees();
            messageThematique.textContent = 'Thématique ajoutée.';
        } catch (erreur) {
            messageThematique.textContent = erreur.message;
        }
    });

    liste.addEventListener('click', (evenement) => {
        const bouton = evenement.target.closest('[data-action]');
        if (!bouton) return;
        const podcast = podcasts.find((element) => element.id === bouton.dataset.id);
        if (!podcast) return;

        if (bouton.dataset.action === 'modifier') {
            formulaireEdition.elements.id.value = podcast.id;
            formulaireEdition.elements.titre.value = podcast.titre;
            formulaireEdition.elements.description.value = podcast.description;
            formulaireEdition.elements.url.value = podcast.url;
            remplirSelecteur(selecteurEdition, thematiques, podcast.thematique);
            dialogue.classList.remove('hidden');
            dialogue.classList.add('flex');
            dialogue.setAttribute('aria-hidden', 'false');
            formulaireEdition.elements.titre.focus();
        }

        if (bouton.dataset.action === 'supprimer' && window.confirm(`Supprimer « ${podcast.titre} » ?`)) {
            appelerApi(`${API_URL}?action=podcast&id=${encodeURIComponent(podcast.id)}`, { method: 'DELETE' })
                .then(chargerDonnees)
                .catch((erreur) => window.alert(erreur.message));
        }
    });

    function fermerDialogue() {
        dialogue.classList.add('hidden');
        dialogue.classList.remove('flex');
        dialogue.setAttribute('aria-hidden', 'true');
    }

    document.querySelector('#edit-close').addEventListener('click', fermerDialogue);
    document.querySelector('#edit-cancel').addEventListener('click', fermerDialogue);
    dialogue.addEventListener('click', (evenement) => {
        if (evenement.target === dialogue) fermerDialogue();
    });

    formulaireEdition.addEventListener('submit', async (evenement) => {
        evenement.preventDefault();
        const formulaire = new FormData(formulaireEdition);
        const podcast = Object.fromEntries(formulaire.entries());

        try {
            await appelerApi(`${API_URL}?action=modifier-podcast`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                body: new URLSearchParams(podcast),
            });
            fermerDialogue();
            await chargerDonnees();
        } catch (erreur) {
            window.alert(erreur.message);
        }
    });

    chargerDonnees();
}

function initialiserAjout() {
    const formulaire = document.querySelector('#podcast-form');
    if (!formulaire) return;

    const selecteur = document.querySelector('#thematique');
    const message = document.querySelector('#form-message');

    appelerApi(`${API_URL}?action=liste`)
        .then((resultat) => remplirSelecteur(selecteur, resultat.thematiques || []))
        .catch((erreur) => {
            message.textContent = `Les thématiques n’ont pas pu être chargées : ${erreur.message}`;
        });

    formulaire.addEventListener('submit', async (evenement) => {
        evenement.preventDefault();
        message.textContent = '';
        const donnees = Object.fromEntries(new FormData(formulaire).entries());

        try {
            await appelerApi(`${API_URL}?action=podcast`, optionsJson('POST', donnees));
            window.location.href = 'index.php';
        } catch (erreur) {
            message.textContent = erreur.message;
        }
    });
}

initialiserBibliotheque();
initialiserAjout();
