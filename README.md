# Gestion de podcasts

Application d’administration de podcasts et de thématiques. Elle utilise PHP orienté objet, Tailwind CSS, JavaScript, Fetch API et des fichiers JSON. Aucun framework ni base SQL n’est nécessaire.

## Structure

```text
.
|-- index.html
|-- assets/js/app.js
`-- backend/
    |-- api/api.php
    |-- classes/
    |   |-- Podcast.php
    |   `-- Thematique.php
    |-- dao/
    |   |-- PodcastDAO.php
    |   `-- ThematiqueDAO.php
    |-- data/
    |   |-- podcasts.json
    |   `-- thematiques.json
    `-- services/
        |-- BaseService.php
        |-- PodcastService.php
        `-- ThematiqueService.php
```

## Rôle Des Couches

- `index.html` et `assets/js/app.js` affichent les vues et envoient les requêtes Fetch.
- `backend/api/api.php` reçoit les requêtes HTTP et les dirige vers le service approprié.
- `backend/services/` applique les règles métier et prépare les réponses JSON.
- `backend/dao/` lit et enregistre les objets dans les fichiers JSON.
- `backend/classes/` définit les entités Podcast et Thematique.
- `backend/data/` conserve les données de l’application.

## Lancer L’Application

Depuis la racine du projet, démarrez le serveur PHP intégré :

```bash
php -S localhost:8000
```

Ouvrez ensuite <http://localhost:8000>. PHP doit pouvoir écrire dans `backend/data/`.

## Fonctionnalités

- Afficher, ajouter, modifier et supprimer des podcasts.
- Ajouter et afficher les thématiques, puis associer chaque podcast à une thématique existante.
- Afficher des notifications et un état de chargement pendant les actions.
- Conserver les données localement en JSON.

## API

Toutes les routes passent par `backend/api/api.php?resource=...` :

| Ressource | Méthode | Action |
|---|---|---|
| `podcasts` | `GET` | Lister les podcasts; ajouter `&id=1` pour un seul podcast |
| `podcasts` | `POST` | Ajouter un podcast |
| `podcasts&id=1` | `PUT` | Modifier un podcast |
| `podcasts&id=1` | `DELETE` | Supprimer un podcast |
| `themes` | `GET` | Lister les thématiques |
| `themes` | `POST` | Ajouter une thématique |

Les requêtes d’ajout et de modification envoient du JSON. Un podcast exige un titre, une description et l’identifiant d’une thématique existante.