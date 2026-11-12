<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ThematiqueDAO.php';

class ThematiqueService extends BaseService
{
    private ThematiqueDAO $thematiqueDAO;

    public function __construct()
    {
        $this->thematiqueDAO = new ThematiqueDAO();
    }

    public function listerThematiques(): array
    {
        $thematiques = $this->thematiqueDAO->AfficherTous();
        return array_map(function (Thematique $thematique): array {
            return $thematique->toArray();
        }, $thematiques);
    }

    public function ajouterThematique(array $input): array
    {
        try {
            $name = trim((string) ($input['name'] ?? ''));
            if ($name === '') {
                throw new InvalidArgumentException('Le nom de la thématique est obligatoire.');
            }
            $thematique = $this->thematiqueDAO->ajouter($name);
            http_response_code(201);
            return $thematique->toArray();
        } catch (Throwable $error) {
            if ($error instanceof InvalidArgumentException && $error->getMessage() === 'Cette thématique existe déjà.') {
                http_response_code(409);
                return ['erreur' => $error->getMessage()];
            }
            return $this->repondreErreur($error);
        }
    }
}