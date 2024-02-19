<?php

namespace App\Models;

use App\Repositories\MovieRepository;

#[\AllowDynamicProperties]
class Movie
{
    private ?int $id = null;

    private int $userId;

    private string $title;

    private string $format;
    private string $releaseYear = '';

    protected string $created_at;

    public function create(): void
    {
        $repository = new MovieRepository();
        $this->id = $repository->create($this->title, $this->format, $this->userId, $this->releaseYear);
    }

    public function update(): void
    {
        $repository = new MovieRepository();
        $repository->update($this->id, $this->title, $this->format);
    }

    public function delete(): void
    {
        $repository = new MovieRepository();
        $repository->delete($this->id);
    }

    public static function getById(int $id): ?Movie
    {
        $repository = new MovieRepository();
        return $repository->getById($id);
    }

    public static function getAllMovies(): ?array
    {
        $repository = new MovieRepository();
        return $repository->getAllMovies();
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReleaseYear(): ?string
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?string $releaseYear): void
    {
        $this->releaseYear = $releaseYear;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getActors(): array
    {
        return Actor::getActorsByMovieId($this->getId());
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public static function getMoviesByCustomQuery($sql): ?array
    {
        $repository = new MovieRepository();
        return $repository->getMoviesByCustomQuery($sql);
    }

    public static function getMoviesByUserId($sql): ?array
    {
        $repository = new MovieRepository();
        return $repository->getMoviesByUserId($sql);
    }
}
