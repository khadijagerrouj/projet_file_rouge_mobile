<?php

require_once __DIR__ . '/../classes/Thematique.php';

class ThematiqueDAO
{
    private static string $dataFile = __DIR__ . '/../data/thematiques.json';

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

        $thematiques = [];
        foreach ($data as $row) {
            $thematiques[] = new Thematique((int) $row['id'], (string) $row['name']);
        }
        return $thematiques;
    }

    public function getById(int $id): ?Thematique
    {
        foreach ($this->AfficherTous() as $thematique) {
            if ($thematique->getId() === $id) {
                return $thematique;
            }
        }
        return null;
    }

    public function nomExiste(string $name): bool
    {
        $name = strtolower(trim($name));
        foreach ($this->AfficherTous() as $thematique) {
            if (strtolower($thematique->getName()) === $name) {
                return true;
            }
        }
        return false;
    }

    public function ajouter(string $name): Thematique
    {
        if ($this->nomExiste($name)) {
            throw new InvalidArgumentException('Cette thématique existe déjà.');
        }
        $thematiques = $this->AfficherTous();
        $ids = array_map(function (Thematique $thematique): int {
            return $thematique->getId();
        }, $thematiques);
        $id = empty($ids) ? 1 : max($ids) + 1;
        $thematique = new Thematique($id, $name);
        $thematiques[] = $thematique;
        $this->sauvegarder($thematiques);
        return $thematique;
    }

    private function sauvegarder(array $thematiques): void
    {
        $data = array_map(function (Thematique $thematique): array {
            return $thematique->toArray();
        }, $thematiques);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents(self::$dataFile, $json, LOCK_EX) === false) {
            throw new RuntimeException('Impossible d’enregistrer les thématiques.');
        }
    }
}