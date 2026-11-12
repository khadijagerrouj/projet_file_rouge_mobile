<?php

class Podcast
{
    private int $id;
    private string $title;
    private string $description;
    private string $audioUrl;
    private int $themeId;
    private string $createdAt;

    public function __construct(int $id, string $title, string $description, string $audioUrl, int $themeId, string $createdAt = '')
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setAudioUrl($audioUrl);
        $this->setThemeId($themeId);
        $this->createdAt = $createdAt !== '' ? $createdAt : date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAudioUrl(): string
    {
        return $this->audioUrl;
    }

    public function getThemeId(): int
    {
        return $this->themeId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setId(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('L’identifiant du podcast doit être positif.');
        }
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $title = trim($title);
        if (strlen($title) < 2) {
            throw new InvalidArgumentException('Le titre doit contenir au moins 2 caractères.');
        }
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $description = trim($description);
        if ($description === '') {
            throw new InvalidArgumentException('La description est obligatoire.');
        }
        $this->description = $description;
    }

    public function setAudioUrl(string $audioUrl): void
    {
        $audioUrl = trim($audioUrl);
        if ($audioUrl !== '' && filter_var($audioUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Le lien audio doit être une URL valide.');
        }
        $this->audioUrl = $audioUrl;
    }

    public function setThemeId(int $themeId): void
    {
        if ($themeId < 1) {
            throw new InvalidArgumentException('Choisissez une thématique valide.');
        }
        $this->themeId = $themeId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'audio_url' => $this->audioUrl,
            'theme_id' => $this->themeId,
            'created_at' => $this->createdAt
        ];
    }
}