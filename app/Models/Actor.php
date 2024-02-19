<?php

namespace App\Models;

use App\Repositories\ActorRepository;

#[\AllowDynamicProperties]
class Actor
{
    protected int $id;

    protected int $movieId;

    protected string $actorName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getActorName(): string
    {
        return $this->actorName;
    }

    public function getMovieId(): int
    {
        return $this->movieId;
    }

    public function setActorName(string $actorName): void
    {
        $this->actorName = $actorName;
    }

    public function setMovieId(int $movieId): void
    {
        $this->movieId = $movieId;
    }

    public function create(): void
    {
        $repository = new ActorRepository();
        $this->id = $repository->create($this->movieId, $this->actorName);
    }

    public function update(): void
    {
        $repository = new ActorRepository();
        $repository->update($this->id, $this->movieId, $this->actorName);
    }

    public function delete(): void
    {
        $repository = new ActorRepository();
        $repository->delete($this->id);
    }

    public static function getById(int $id): ?self
    {
        $repository = new ActorRepository();
        return $repository->getById($id);
    }

    public static function getActorsByMovieId(int $movieId): array
    {
        $repository = new ActorRepository();
        return $repository->getActorsByMovieId($movieId);
    }
}
