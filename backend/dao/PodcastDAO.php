<?php

require_once __DIR__ . '/../classes/Podcast.php';

class PodcastDAO
{
    private static string $dataFile = __DIR__ . '/../data/podcasts.json';

    public function AfficherTous(): array
    {
        if (!file_exists(self::$dataFile)) {
            file_put_contents(self::$dataFile, json_encode([], JSON_PRETTY_PRINT));
            return [];
        }

        $data = json_decode(file_get_contents(self::$dataFile), true);
        if (!is_array($data)) {
            return [];
        }

        $podcasts = [];
        foreach ($data as $row) {
            $podcasts[] = new Podcast(
                (int) $row['id'],
                (string) $row['title'],
                (string) $row['description'],
                (string) ($row['audio_url'] ?? ''),
                (int) ($row['theme_id'] ?? 0),
                (string) ($row['created_at'] ?? '')
            );
        }
        return $podcasts;
    }

    public function getById(int $id): ?Podcast
    {
        foreach ($this->AfficherTous() as $podcast) {
            if ($podcast->getId() === $id) {
                return $podcast;
            }
        }
        return null;
    }

    public function ajouter(string $title, string $description, string $audioUrl, int $themeId): Podcast
    {
        $podcasts = $this->AfficherTous();
        $ids = array_map(function (Podcast $podcast): int {
            return $podcast->getId();
        }, $podcasts);
        $id = empty($ids) ? 1 : max($ids) + 1;
        $podcast = new Podcast($id, $title, $description, $audioUrl, $themeId);
        $podcasts[] = $podcast;
        $this->sauvegarder($podcasts);
        return $podcast;
    }

    public function modifier(int $id, string $title, string $description, string $audioUrl, int $themeId): ?Podcast
    {
        $podcasts = $this->AfficherTous();
        foreach ($podcasts as $podcast) {
            if ($podcast->getId() === $id) {
                $podcast->setTitle($title);
                $podcast->setDescription($description);
                $podcast->setAudioUrl($audioUrl);
                $podcast->setThemeId($themeId);
                $this->sauvegarder($podcasts);
                return $podcast;
            }
        }
        return null;
    }

    public function supprimer(int $id): bool
    {
        $podcasts = $this->AfficherTous();
        $remaining = array_values(array_filter($podcasts, function (Podcast $podcast) use ($id): bool {
            return $podcast->getId() !== $id;
        }));
        if (count($remaining) === count($podcasts)) {
            return false;
        }
        $this->sauvegarder($remaining);
        return true;
    }

    private function sauvegarder(array $podcasts): void
    {
        $data = array_map(function (Podcast $podcast): array {
            return $podcast->toArray();
        }, $podcasts);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents(self::$dataFile, $json, LOCK_EX) === false) {
            throw new RuntimeException('Impossible d’enregistrer les podcasts.');
        }
    }
}