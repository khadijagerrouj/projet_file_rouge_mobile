<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes podcasts | Ondes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans', 'sans-serif'], display: ['Fraunces', 'serif'] } } } };
    </script>
    <style>
        :root { color-scheme: light; }
        body { background-color: #f4f5ef; background-image: radial-gradient(#dfe3d8 0.7px, transparent 0.7px); background-size: 16px 16px; }
        dialog::backdrop { background: rgb(19 49 43 / 55%); }
    </style>
</head>
<body class="min-h-screen font-sans text-[#1d302b] antialiased">
    <header class="bg-[#173e36] text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="index.php" class="flex items-center gap-3" aria-label="Ondes, accueil">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#e8bd62] text-[#173e36]" aria-hidden="true">
                    <span class="flex h-4 items-center gap-[2px]"><i class="h-2 w-[2px] rounded bg-current"></i><i class="h-4 w-[2px] rounded bg-current"></i><i class="h-3 w-[2px] rounded bg-current"></i><i class="h-1.5 w-[2px] rounded bg-current"></i></span>
                </span>
                <span class="text-lg font-bold tracking-wide">ondes<span class="text-[#e8bd62]">.</span></span>
            </a>
            <nav class="flex items-center gap-2 text-sm font-semibold sm:gap-5">
                <a href="index.php" class="rounded px-2 py-2 text-white" aria-current="page">Bibliothèque</a>
                <a href="ajouter.php" class="rounded bg-[#e8bd62] px-3 py-2 text-[#173e36] transition hover:bg-[#f0ce82]">+ Ajouter un podcast</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-9 sm:px-8 sm:py-12">
        <div class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-[#658078]">Votre espace d’écoute</p>
                <h1 class="font-display text-4xl leading-tight text-[#173e36] sm:text-5xl">La bibliothèque</h1>
                <p class="mt-2 text-sm text-[#687972]">Retrouvez et organisez vos podcasts préférés.</p>
            </div>
            <div class="flex items-center gap-3">
                <label for="podcast-search" class="sr-only">Rechercher un podcast</label>
                <input id="podcast-search" type="search" placeholder="Rechercher…" class="w-full rounded-lg border border-[#d8ded5] bg-white px-4 py-2.5 text-sm outline-none transition placeholder:text-[#9aa49c] focus:border-[#648c78] sm:w-56">
                <span id="podcast-count" class="shrink-0 rounded-full bg-[#e5eadd] px-3 py-2 text-xs font-bold text-[#526e61]">0 épisode</span>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_280px]">
            <section aria-labelledby="podcasts-title">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <h2 id="podcasts-title" class="text-sm font-bold text-[#354b43]">Tous les podcasts</h2>
                    <div id="theme-filters" class="hidden items-center gap-2 overflow-x-auto sm:flex"></div>
                </div>
                <p id="loading-state" class="rounded-lg border border-dashed border-[#cbd5cb] bg-white/70 px-5 py-10 text-center text-sm text-[#687972]">Chargement des podcasts…</p>
                <div id="podcast-list" class="grid gap-4 sm:grid-cols-2"></div>
            </section>

            <aside class="h-fit border-t border-[#dce1d9] pt-6 lg:border-l lg:border-t-0 lg:pl-7 lg:pt-0" aria-labelledby="themes-title">
                <div class="mb-5 flex items-center justify-between">
                    <h2 id="themes-title" class="font-display text-2xl text-[#173e36]">Thématiques</h2>
                    <span id="theme-count" class="text-xs font-semibold text-[#78877e]">0</span>
                </div>
                <ul id="theme-list" class="mb-6 space-y-1"></ul>
                <form id="theme-form" class="border-t border-[#dce1d9] pt-5">
                    <label for="theme-name" class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526e61]">Nouvelle thématique</label>
                    <div class="flex gap-2">
                        <input id="theme-name" name="nom" maxlength="50" required placeholder="Ex. Histoire" class="min-w-0 flex-1 rounded-md border border-[#d8ded5] bg-white px-3 py-2 text-sm outline-none focus:border-[#648c78]">
                        <button type="submit" aria-label="Ajouter la thématique" class="grid h-10 w-10 shrink-0 place-items-center rounded-md bg-[#173e36] text-xl text-white transition hover:bg-[#27594d]">+</button>
                    </div>
                    <p id="theme-message" class="mt-2 min-h-5 text-xs text-[#658078]" role="status"></p>
                </form>
            </aside>
        </div>
    </main>

    <div id="edit-dialog" class="fixed inset-0 z-20 hidden items-center justify-center bg-[#13312b]/60 p-4" role="dialog" aria-modal="true" aria-labelledby="edit-title" aria-hidden="true">
        <form id="edit-form" class="w-full max-w-lg rounded-lg bg-[#fbfcf8] p-6 shadow-2xl sm:p-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div><p class="mb-1 text-xs font-bold uppercase tracking-wide text-[#658078]">Modifier la fiche</p><h2 id="edit-title" class="font-display text-3xl text-[#173e36]">Votre podcast</h2></div>
                <button id="edit-close" type="button" aria-label="Fermer" class="rounded px-2 text-2xl leading-none text-[#687972] hover:bg-[#e9eee6]">&times;</button>
            </div>
            <input id="edit-id" name="id" type="hidden">
            <div class="space-y-4">
                <div><label for="edit-titre" class="mb-1 block text-sm font-semibold">Titre</label><input id="edit-titre" name="titre" required maxlength="100" class="w-full rounded-md border border-[#d8ded5] bg-white px-3 py-2.5 text-sm outline-none focus:border-[#648c78]"></div>
                <div><label for="edit-description" class="mb-1 block text-sm font-semibold">Description</label><textarea id="edit-description" name="description" rows="3" maxlength="500" class="w-full resize-y rounded-md border border-[#d8ded5] bg-white px-3 py-2.5 text-sm outline-none focus:border-[#648c78]"></textarea></div>
                <div><label for="edit-url" class="mb-1 block text-sm font-semibold">Lien d’écoute <span class="font-normal text-[#839087]">(facultatif)</span></label><input id="edit-url" name="url" type="url" placeholder="https://…" class="w-full rounded-md border border-[#d8ded5] bg-white px-3 py-2.5 text-sm outline-none focus:border-[#648c78]"></div>
                <div><label for="edit-thematique" class="mb-1 block text-sm font-semibold">Thématique</label><select id="edit-thematique" name="thematique" class="w-full rounded-md border border-[#d8ded5] bg-white px-3 py-2.5 text-sm outline-none focus:border-[#648c78]"></select></div>
            </div>
            <div class="mt-7 flex justify-end gap-3"><button id="edit-cancel" type="button" class="rounded-md px-4 py-2 text-sm font-semibold text-[#526e61] hover:bg-[#e9eee6]">Annuler</button><button type="submit" class="rounded-md bg-[#173e36] px-5 py-2 text-sm font-bold text-white hover:bg-[#27594d]">Enregistrer</button></div>
        </form>
    </div>

    <footer class="mx-auto max-w-7xl px-5 pb-8 text-xs text-[#829087] sm:px-8">Ondes <span aria-hidden="true">·</span> Vos podcasts, à votre rythme.</footer>
    <script src="app.js" defer></script>
</body>
</html>
