# Diagramme de classes

## Modèle métier

Le modèle distingue la fiche d'un podcast de ses épisodes. Chaque épisode appartient à un seul podcast ; les podcasts sont organisés par catégorie.


_Figure — Modèle métier cible pour la gestion des podcasts et des épisodes._

## Responsabilités et règles

- **Podcast** contient les informations générales d'une émission et son statut.
- **Episode** contient les métadonnées et la référence vers la ressource audio.
- **Categorie** regroupe les podcasts selon leur thème.
- **Utilisateur** représente le gestionnaire autorisé à administrer les contenus.

Un épisode appartient à un seul podcast. Un épisode brouillon ou archivé n'est pas proposé dans le catalogue public. Les champs requis et la disponibilité de la ressource audio sont contrôlés avant publication.
