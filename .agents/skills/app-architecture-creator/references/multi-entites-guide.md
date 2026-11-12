# Guide de Gestion Multi-Entités

## Principe

Chaque entité possède un modèle, un fichier JSON, un DAO et un service. Les routes API peuvent être séparées ou centralisées dans `backend/api/api.php?resource=...`.

| Couche | Exemple pour `Podcast` |
|---|---|
| Modèle | `backend/classes/Podcast.php` |
| Données | `backend/data/podcasts.json` |
| DAO | `backend/dao/PodcastDAO.php` |
| Service | `backend/services/PodcastService.php` |
| API | `backend/api/api.php?resource=podcasts` |

## Relations

L'entité liée conserve l'identifiant de la thématique (par exemple `theme_id`). Le service vérifie que la thématique existe et peut associer le libellé à la réponse destinée au frontend.

```php
$themeNames = [];
foreach ($themes as $theme) {
    $themeNames[$theme->getId()] = $theme->getName();
}

$response['theme_name'] = $themeNames[$podcast->getThemeId()] ?? 'Thématique inconnue';
```

Le DAO reste responsable d'une seule entité; la jointure de présentation se fait dans le service.

## API Multi-Ressource

```text
GET    backend/api/api.php?resource=podcasts
POST   backend/api/api.php?resource=podcasts
PUT    backend/api/api.php?resource=podcasts&id=1
DELETE backend/api/api.php?resource=podcasts&id=1
GET    backend/api/api.php?resource=themes
POST   backend/api/api.php?resource=themes
```

Les listes déroulantes de relations se chargent depuis l'API de l'entité liée et envoient la clé étrangère dans le JSON.