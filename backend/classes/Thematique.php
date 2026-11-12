<?php

class Thematique
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setId(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('L’identifiant de la thématique doit être positif.');
        }
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Le nom de la thématique est obligatoire.');
        }
        $this->name = $name;
    }

    public function toArray(): array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}