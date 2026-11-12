<?php
require_once __DIR__ . '/../dao/{{Entity}}DAO.php';
require_once __DIR__ . '/BaseService.php';

class {{Entity}}Service extends BaseService {
    private {{Entity}}DAO $dao;

    public function __construct() {
        $this->dao = new {{Entity}}DAO();
    }

    public function lister{{Entities}}(?int $id = null): array {
        if ($id !== null) {
            $item = $this->dao->getById($id);
            if ($item === null) {
                http_response_code(404);
                return ['erreur' => '{{Entity}} introuvable.'];
            }
            http_response_code(200);
            return $item->toArray();
        }

        $items = $this->dao->AfficherTous();
        http_response_code(200);
        return array_map(fn($item) => $item->toArray(), $items);
    }

    public function ajouter{{Entity}}(array $input): array {
        try {
            $item = $this->dao->ajouter({{serviceInputParams}});
            http_response_code(201);
            return $item->toArray();
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            return ['erreur' => $error->getMessage()];
        } catch (Throwable $error) {
            http_response_code(500);
            return ['erreur' => 'Une erreur est survenue.'];
        }
    }

    public function modifier{{Entity}}(int $id, array $input): array {
        try {
            $item = $this->dao->modifier($id, {{serviceInputParams}});
            if ($item === null) {
                http_response_code(404);
                return ['erreur' => '{{Entity}} introuvable.'];
            }
            http_response_code(200);
            return $item->toArray();
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            return ['erreur' => $error->getMessage()];
        } catch (Throwable $error) {
            http_response_code(500);
            return ['erreur' => 'Une erreur est survenue.'];
        }
    }

    public function supprimer{{Entity}}(int $id): array {
        if (!$this->dao->supprimer($id)) {
            http_response_code(404);
            return ['erreur' => '{{Entity}} introuvable.'];
        }
        http_response_code(200);
        return ['message' => '{{Entity}} supprimé(e).'];
    }
}