---
name: app-architecture-creator
description: "À utiliser pour concevoir, structurer, coder ou étendre une application web complète (frontend et backend) selon l'architecture N-tiers légère Vanilla PHP / DAO JSON / Tailwind CSS / Vanilla JS observée à SoliCode, quel que soit le nombre de classes ou d'entités (1, 2 ou N classes). Permet de créer un nouveau projet ou d'ajouter une entité à un projet existant avec la même simplicité."
---

# App Architecture Creator

Ce skill permet de concevoir, développer et étendre des applications web basées sur l'architecture N-tiers légère sans framework, inspirée du projet `liveCoding_Sprint_3`. Il garantit un code pédagogique, propre, typé et immédiatement fonctionnel, quel que soit le nombre d'entités du projet.

## Quand utiliser ce skill

- Créer une nouvelle application web complète (Frontend Tailwind + Backend PHP POO + persistance JSON).
- Ajouter une ou plusieurs nouvelles classes/entités à un projet existant en respectant la même séparation en couches.
- Structurer un CRUD rapide, modulaire et lisible pour des apprenants ou prototypes techniques.
- Mettre en place les relations entre entités (1-N, N-N) de manière simple sans ORM ni configuration complexe.

Ne pas utiliser pour :
- Des architectures basées sur des frameworks imposés (Laravel, Symfony, React, Angular, Vue).
- Des applications nécessitant obligatoirement une base SQL relationnelle avec migrations, sauf demande explicite de migration du DAO JSON vers PDO/MySQL.

## Architecture de Référence

```text
mon-projet/
├── index.html
├── assets/
│   └── js/app.js
└── backend/
    ├── api/api.php
    ├── classes/[Entity].php
    ├── dao/[Entity]DAO.php
    ├── data/[Entities].json
    └── services/
        ├── BaseService.php
        └── [Entity]Service.php
```

## Workflow d'Exécution

### Étape 1 : Cadrage des Entités et Relations
1. Lister chaque entité avec ses attributs et leurs types scalaires (`int`, `string`, `float`, `bool`).
2. Identifier les clés primaires (toujours `int $id`) et les clés étrangères éventuelles.
3. Pour plusieurs entités, utiliser un endpoint par ressource ou un routeur unifié. Consulter [references/multi-entites-guide.md](./references/multi-entites-guide.md).

### Étape 2 : Arborescence
Créer ou réutiliser les dossiers `backend/classes/`, `backend/dao/`, `backend/services/`, `backend/api/`, `backend/data/` et `assets/js/`.

### Étape 3 : Modèles
Pour chaque entité, utiliser le modèle [resources/EntityTemplate.php](./resources/EntityTemplate.php) : propriétés privées typées, constructeur, getters et aucune logique de stockage.

### Étape 4 : Données
Initialiser chaque fichier JSON dans `backend/data/` avec `[]` ou des données de démonstration valides.

### Étape 5 : DAO
Utiliser [resources/DAOTemplate.php](./resources/DAOTemplate.php). Le DAO lit et écrit le JSON, instancie les entités, valide les champs, calcule les identifiants et expose `getById`, `ajouter`, `modifier` et `supprimer`.

### Étape 6 : Services
Utiliser `BaseService.php` et [resources/ServiceTemplate.php](./resources/ServiceTemplate.php). Un service orchestre les règles métier, les DAO, la validation et les réponses destinées à l'API pour la lecture, la création, la modification et la suppression.

### Étape 7 : API
Utiliser [resources/ApiTemplate.php](./resources/ApiTemplate.php). L'API définit les en-têtes JSON, lit la méthode HTTP et l'identifiant éventuel, puis délègue GET, POST, PUT ou DELETE au service.

### Étape 8 : Interface
Utiliser [resources/indexTemplate.html](./resources/indexTemplate.html) pour une interface responsive Tailwind avec navigation, formulaire et notifications.

### Étape 9 : JavaScript
Utiliser [resources/appTemplate.js](./resources/appTemplate.js) pour les appels Fetch, états de chargement, toasts et rendu DOM.

### Marqueurs Des Templates

Les textes `{{...}}` sont des marqueurs de génération et non des valeurs métier. Les marqueurs de nom (`Entity`, `Entities`, `entityLower`, `entitiesLower`, `apiEndpoint`) définissent les noms de fichiers, classes et sélecteurs. Les marqueurs de modèle (`properties`, `constructorParams`, `constructorAssignments`, `getters`, `itemArgs`) définissent les attributs de l'entité. `addParams`, `addEntityArgs`, `validationCondition` et `serviceInputParams` relient les champs aux DAO/services. `tableHeaders`, `tableRowColumns` et `formFields` contiennent les fragments HTML nécessaires. Dans `appTemplate.js`, `formPayloadMappings` est une liste de noms de champs séparés par des virgules; ces noms doivent correspondre aux attributs `name` du formulaire et aux clés de l'API. Les templates restent syntaxiquement valides avant leur personnalisation.

### Étape 10 : Relations
Pour une relation, conserver l'identifiant de la clé étrangère dans le modèle, vérifier qu'il existe et assembler les libellés dans le service. Consulter [references/multi-entites-guide.md](./references/multi-entites-guide.md).

## Règles de Simplicité

1. Pas de framework ni de dépendance complexe : le projet doit démarrer avec `php -S localhost:8000`.
2. Utiliser des noms de méthodes explicites.
3. Typer les propriétés, paramètres et retours PHP.
4. Valider les données avant leur écriture dans le JSON.
5. Fournir un retour utilisateur et un état de chargement pour chaque action.

## Validation

1. Exécuter `php -l` sur chaque fichier PHP applicatif.
2. Vérifier que chaque fichier JSON est valide.
3. Vérifier les chemins `require_once`.
4. Vérifier le chargement de Tailwind et du JavaScript.