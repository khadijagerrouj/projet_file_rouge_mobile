<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un podcast | Ondes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans', 'sans-serif'], display: ['Fraunces', 'serif'] } } } };
    </script>
    <style>
        body { background-color: #f4f5ef; background-image: radial-gradient(#dfe3d8 0.7px, transparent 0.7px); background-size: 16px 16px; }
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
                <a href="index.php" class="rounded px-2 py-2 text-white/80 transition hover:text-white">Bibliothèque</a>
                <a href="ajouter.php" class="rounded bg-[#e8bd62] px-3 py-2 text-[#173e36]" aria-current="page">+ Ajouter un podcast</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-5 py-10 sm:px-8 sm:py-14">
        <a href="index.php" class="mb-7 inline-flex items-center gap-2 text-sm font-semibold text-[#658078] hover:text-[#173e36]"><span aria-hidden="true">←</span> Retour à la bibliothèque</a>
        <div class="mb-7">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-[#658078]">Enrichir votre collection</p>
            <h1 class="font-display text-4xl leading-tight text-[#173e36] sm:text-5xl">Ajouter un podcast</h1>
            <p class="mt-2 text-sm text-[#687972]">Quelques détails suffisent pour garder une trace de votre découverte.</p>
        </div>

        <form id="podcast-form" class="rounded-lg border border-[#e2e6de] bg-white p-5 shadow-sm sm:p-8">
            <div class="space-y-5">
                <div>
                    <label for="titre" class="mb-1.5 block text-sm font-bold">Titre <span class="text-[#d36349]">*</span></label>
                    <input id="titre" name="titre" required maxlength="100" placeholder="Le nom de l’épisode ou du podcast" class="w-full rounded-md border border-[#d8ded5] bg-[#fdfdfa] px-4 py-3 text-sm outline-none transition focus:border-[#648c78] focus:ring-2 focus:ring-[#648c78]/15">
                </div>
                <div>
                    <label for="description" class="mb-1.5 block text-sm font-bold">Description <span class="font-normal text-[#839087]">(facultatif)</span></label>
                    <textarea id="description" name="description" rows="4" maxlength="500" placeholder="De quoi parle cet épisode ?" class="w-full resize-y rounded-md border border-[#d8ded5] bg-[#fdfdfa] px-4 py-3 text-sm outline-none transition focus:border-[#648c78] focus:ring-2 focus:ring-[#648c78]/15"></textarea>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="url" class="mb-1.5 block text-sm font-bold">Lien d’écoute <span class="font-normal text-[#839087]">(facultatif)</span></label>
                        <input id="url" name="url" type="url" placeholder="https://…" class="w-full rounded-md border border-[#d8ded5] bg-[#fdfdfa] px-4 py-3 text-sm outline-none transition focus:border-[#648c78] focus:ring-2 focus:ring-[#648c78]/15">
                    </div>
                    <div>
                        <label for="thematique" class="mb-1.5 block text-sm font-bold">Thématique</label>
                        <select id="thematique" name="thematique" class="w-full rounded-md border border-[#d8ded5] bg-[#fdfdfa] px-4 py-3 text-sm outline-none transition focus:border-[#648c78] focus:ring-2 focus:ring-[#648c78]/15">
                            <option value="">Sans thématique</option>
                        </select>
                    </div>
                </div>
            </div>
            <p id="form-message" class="mt-4 min-h-5 text-sm text-[#a44835]" role="status"></p>
            <div class="mt-5 flex flex-col-reverse justify-end gap-3 border-t border-[#edf0e9] pt-5 sm:flex-row">
                <a href="index.php" class="rounded-md px-5 py-3 text-center text-sm font-semibold text-[#526e61] hover:bg-[#f1f3ed]">Annuler</a>
                <button type="submit" class="rounded-md bg-[#173e36] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#27594d]">Ajouter à ma bibliothèque</button>
            </div>
        </form>
        <p class="mt-4 text-xs text-[#829087]">Vous pourrez modifier ces informations depuis votre bibliothèque.</p>
    </main>
    <script src="app.js" defer></script>
</body>
</html>
