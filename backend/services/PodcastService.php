<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PodcastDAO.php';
require_once __DIR__ . '/../dao/ThematiqueDAO.php';

class PodcastService extends BaseService
{
    private PodcastDAO $podcastDAO;
    private ThematiqueDAO $thematiqueDAO;

    public function __construct()
    {
        $this->podcastDAO = new PodcastDAO();
        $this->thematiqueDAO = new ThematiqueDAO();
    }

    public function listerPodcasts(?int $id = null): array
    {
        if ($id !== null) {
            $podcast = $this->podcastDAO->getById($id);
            if ($podcast === null) {
                http_response_code(404);
                return ['erreur' => 'Podcast introuvable.'];
            }
            http_response_code(200);
            return $this->formaterPodcast($podcast);
        }

        $podcasts = $this->podcastDAO->AfficherTous();
        $thematiques = $this->thematiqueDAO->AfficherTous();
        $nomsThematiques = [];
        foreach ($thematiques as $thematique) {
            $nomsThematiques[$thematique->getId()] = $thematique->getName();
        }

        $resultat = [];
        foreach ($podcasts as $podcast) {
            $resultat[] = $this->formaterPodcast($podcast, $nomsThematiques);
        }
        http_response_code(200);
        return $resultat;
    }

    public function ajouterPodcast(array $input): array
    {
        try {
            $this->validerDonnees($input);
            $podcast = $this->podcastDAO->ajouter(
                (string) $input['title'],
                (string) $input['description'],
                (string) ($input['audio_url'] ?? ''),
                (int) $input['theme_id']
            );
            http_response_code(201);
            return $this->formaterPodcast($podcast);
        } catch (Throwable $error) {
            return $this->repondreErreur($error);
        }
    }

    public function modifierPodcast(int $id, array $input): array
    {
        try {
            $this->validerDonnees($input);
            $podcast = $this->podcastDAO->modifier(
                $id,
                (string) $input['title'],
                (string) $input['description'],
                (string) ($input['audio_url'] ?? ''),
                (int) $input['theme_id']
            );
            if ($podcast === null) {
                http_response_code(404);
                return ['erreur' => 'Podcast introuvable.'];
            }
            http_response_code(200);
            return $this->formaterPodcast($podcast);
        } catch (Throwable $error) {
            return $this->repondreErreur($error);
        }
    }

    public function supprimerPodcast(int $id): array
    {
        if (!$this->podcastDAO->supprimer($id)) {
            http_response_code(404);
            return ['erreur' => 'Podcast introuvable.'];
        }
        http_response_code(200);
        return ['message' => 'Podcast supprimé.'];
    }

    private function validerDonnees(array $input): void
    {
        if (trim((string) ($input['title'] ?? '')) === '' || trim((string) ($input['description'] ?? '')) === '') {
            throw new InvalidArgumentException('Le titre et la description sont obligatoires.');
        }
        if ($this->thematiqueDAO->getById((int) ($input['theme_id'] ?? 0)) === null) {
            throw new InvalidArgumentException('Choisissez une thématique existante.');
        }
    }

    private function formaterPodcast(Podcast $podcast, array $nomsThematiques = []): array
    {
        $data = $podcast->toArray();
        if (empty($nomsThematiques)) {
            foreach ($this->thematiqueDAO->AfficherTous() as $thematique) {
                $nomsThematiques[$thematique->getId()] = $thematique->getName();
            }
        }
        $data['theme_name'] = $nomsThematiques[$podcast->getThemeId()] ?? 'Thématique inconnue';
        return $data;
    }
}