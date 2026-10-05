<?php

declare(strict_types=1);

class Podcast
{
    public string $id;
    public string $titre;
    public string $description;
    public string $url;
    public string $thematique;
    public string $date;

    public function __construct(
        string $id,
        string $titre,
        string $description,
        string $url,
        string $thematique,
        string $date
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->url = $url;
        $this->thematique = $thematique;
        $this->date = $date;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'url' => $this->url,
            'thematique' => $this->thematique,
            'date' => $this->date,
        ];
    }
}
