<?php

declare(strict_types=1);

class Thematique
{
    public string $id;
    public string $nom;

    public function __construct(string $id, string $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
        ];
    }
}
