<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\Actor;
use App\Models\Database;
use AllowDynamicProperties;
use App\Helpers\DatabaseHelper;
use App\Exceptions\NotFoundObjectException;

#[AllowDynamicProperties]
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
        $sql = "INSERT INTO movies (title, format, user_id, release_year, created_at) VALUES (:title, :format, :user_id, :release_year, NOW())";
        $params = [
            ':title' => $this->title,
            ':format' => $this->format,
            ':user_id' => $this->userId,
            ':release_year' => $this->releaseYear,
        ];

        DatabaseHelper::executeQuery($sql, $params);

        $this->id = Database::getInstance()->getConnection()->lastInsertId();
    }

    public function update(): void
    {
        $sql = "UPDATE movies SET title = :title, format = :format WHERE id = :id";
        $params = [
            ':id' => $this->id,
            ':title' => $this->title,
            ':format' => $this->format
        ];

        DatabaseHelper::executeQuery($sql, $params);
    }

    public function delete(): void
    {
        $sql = "DELETE FROM movies WHERE id = :id";
        $params = [':id' => $this->id];

        DatabaseHelper::executeQuery($sql, $params);
    }

    public static function getmMoviesByUserId(int $userId): array
    {
        $sql = "SELECT * FROM movies WHERE user_id = :userId";
        $params = [':userId' => $userId];

        return DatabaseHelper::executeFetchAll($sql, $params, 'App\Models\Movie');
    }

    /**
     * @throws NotFoundObjectException
     */
    public static function getById(int $id): ?Movie
    {
        $sql = "SELECT * FROM movies WHERE id = :id";
        $params = [':id' => $id];

        $result = DatabaseHelper::executeFetchObject($sql, $params, 'App\Models\Movie');

        return $result ?? throw new NotFoundObjectException();
    }

    public static function getAllMovies(): ?array
    {
        $sql = "SELECT * FROM movies";

        return DatabaseHelper::executeFetchAll($sql, null, 'App\Models\Movie');
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

    public function getActors(): array {
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

    public static function getSurveysByCustomQuery($sql): ?array
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Models\Movie');
            return ($results !== false) ? $results : null;
        } catch (PDOException $e) {
            exit("Error: " . $e->getMessage());
        }
    }
}
