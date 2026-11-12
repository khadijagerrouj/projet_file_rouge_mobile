# Conventions et Patterns d'Architecture

## Vue d'Ensemble

```text
Navigateur (index.html + assets/js/app.js)
    -> Fetch JSON / HTTP
    -> backend/api/api.php
    -> backend/services/*Service.php
    -> backend/dao/*DAO.php
    -> backend/data/*.json
    -> backend/classes/*.php
```

## Modèles

- Une classe représente une entité et ne contient aucune logique de stockage.
- Ses propriétés sont privées et typées; son identifiant est un entier.
- Le constructeur initialise l'entité et des getters typés exposent ses propriétés.

## DAO

- Un DAO par entité gère l'accès au fichier JSON correspondant.
- `AfficherTous(): array` décode le fichier et instancie les objets.
- Les opérations d'ajout et de modification valident les valeurs; la suppression conserve la liste des entités restantes.
- Le DAO propose `getById`, `ajouter`, `modifier` et `supprimer` et sauvegarde les objets dans le JSON.

## Services

- `BaseService.php` est la classe mère des services.
- Un service orchestre les règles métier, fait appel aux DAO et prépare des tableaux destinés à l'API.
- Les règles métier et les réponses HTTP ne doivent pas être placées dans le modèle.
- Les services traitent les erreurs de validation (422), les éléments absents (404) et les erreurs inattendues (500).

## API

- L'API définit `Content-Type: application/json`, lit la méthode HTTP et l'identifiant éventuel, puis délègue GET, POST, PUT et DELETE au service.
- Les réponses sont encodées en JSON avec un statut HTTP cohérent.

## Frontend

- Tailwind CSS fournit une interface responsive de type tableau de bord.
- Les formulaires peuvent être repliés; les notifications utilisent `#toast-container`.
- Le JavaScript utilise Fetch, montre le chargement, rend les réponses et signale les erreurs.
- Échapper les valeurs dynamiques avant de les insérer dans `innerHTML`; préférer `textContent` pour le texte simple.