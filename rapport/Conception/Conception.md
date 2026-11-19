## Principes de conception

La conception s'appuie sur trois entités principales : Podcast, Épisode et Catégorie. Un podcast regroupe ses épisodes, et une catégorie permet de classer plusieurs podcasts. Les champs descriptifs et l'état de publication sont séparés de la ressource audio afin de faciliter la gestion des métadonnées.

Les écrans distinguent l'espace de gestion réservé au gestionnaire du catalogue consulté par l'auditeur. Les formulaires contrôlent les champs requis, tandis que les règles métier interdisent la publication d'un épisode incomplet.
