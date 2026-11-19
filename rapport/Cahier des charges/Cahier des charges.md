## Besoins fonctionnels

### Gestionnaire

- S'authentifier avant d'accéder aux fonctions d'administration.
- Créer, consulter, modifier et archiver une fiche podcast.
- Créer et gérer les épisodes associés à un podcast.
- Renseigner le titre, la description, la date de publication, la durée et l'adresse de la ressource audio d'un épisode.
- Associer une catégorie à un podcast.
- Enregistrer un épisode comme brouillon, puis le publier ou le dépublier.
- Consulter la liste des contenus et leur état de publication.

### Auditeur

- Parcourir les podcasts publiés et consulter leur description.
- Rechercher un podcast par son titre et filtrer le catalogue par catégorie.
- Consulter la liste des épisodes d'un podcast.
- Lancer la lecture d'un épisode publié et accéder aux informations qui lui sont associées.

## Règles de gestion

- Un podcast peut contenir plusieurs épisodes ; un épisode appartient à un seul podcast.
- Un podcast appartient à une catégorie, laquelle peut regrouper plusieurs podcasts.
- Un brouillon n'apparaît pas dans le catalogue public.
- Un épisode ne peut être publié que si ses informations obligatoires et sa ressource audio sont valides.
- Les opérations de création, de modification et de suppression sont réservées au gestionnaire autorisé.
- La suppression définitive d'un podcast ayant des épisodes doit être contrôlée ; l'archivage est préférable afin de préserver l'historique.

## Exigences non fonctionnelles

- L'interface doit être utilisable sur téléphone et ordinateur.
- Les formulaires doivent valider les données et afficher des messages d'erreur compréhensibles.
- Les accès aux fonctions d'administration doivent être protégés.
- Les métadonnées audio doivent être séparées du stockage des fichiers volumineux ; le système conserve une URL ou une référence vers la ressource.
- Les pages de catalogue doivent rester lisibles et permettre une navigation accessible au clavier.

## Hors périmètre de la première version

La monétisation, la transcription automatique, les recommandations personnalisées, les statistiques avancées et la diffusion en direct ne sont pas incluses dans le périmètre initial.
