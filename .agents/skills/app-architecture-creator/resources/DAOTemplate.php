<?php
require_once __DIR__ . '/../classes/{{Entity}}.php';

class {{Entity}}DAO {
    private static string $dataFile = __DIR__ . '/../data/{{Entities}}.json';

    public function AfficherTous(): array {
        if (!file_exists(self::$dataFile)) {
            file_put_contents(self::$dataFile, json_encode([], JSON_PRETTY_PRINT));
            return [];
        }

        $items = json_decode(file_get_contents(self::$dataFile), true) ?? [];
        $data = [];
        foreach ($items as $item) {
            $data[] = new {{Entity}}($item['id']{{itemArgs}});
        }
        return $data;
    }

    public function getById(int $id): ?{{Entity}} {
        foreach ($this->AfficherTous() as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function ajouter({{addParams}}): {{Entity}} {
        if ({{validationCondition}}) {
            throw new InvalidArgumentException('Tous les champs sont obligatoires.');
        }

        $items = $this->AfficherTous();
        $ids = array_map(fn($item) => $item->getId(), $items);
        $id = empty($ids) ? 1 : max($ids) + 1;
        $item = new {{Entity}}($id{{addEntityArgs}});
        $items[] = $item;
        $this->sauvegarder($items);
        return $item;
    }

    public function modifier(int $id, {{addParams}}): ?{{Entity}} {
        if ({{validationCondition}}) {
            throw new InvalidArgumentException('Tous les champs sont obligatoires.');
        }

        $items = $this->AfficherTous();
        foreach ($items as $index => $current) {
            if ($current->getId() === $id) {
                $item = new {{Entity}}($id{{addEntityArgs}});
                $items[$index] = $item;
                $this->sauvegarder($items);
                return $item;
            }
        }
        return null;
    }

    public function supprimer(int $id): bool {
        $items = $this->AfficherTous();
        $remaining = array_values(array_filter($items, fn($item) => $item->getId() !== $id));
        if (count($remaining) === count($items)) {
            return false;
        }
        $this->sauvegarder($remaining);
        return true;
    }

    private function sauvegarder(array $items): void {
        $data = array_map(fn($item) => $item->toArray(), $items);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents(self::$dataFile, $json, LOCK_EX) === false) {
            throw new RuntimeException('Impossible d’enregistrer les données.');
        }
    }
}