<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Helpers\DatabaseHelper;
use App\Models\Database;
use Exception;
use PDO;
use PDOException;

#[AllowDynamicProperties]
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

    public function setSurveyId(int $movieId): void
    {
        $this->movieId = $movieId;
    }

    public function create(): void
    {
        $sql = "INSERT INTO questions (movie_id, actor_name) VALUES (:survey_id, :actor_name)";
        $params = [
            ':movie_id' => $this->movieId,
            ':actor_name' => $this->actorName
        ];

        DatabaseHelper::executeQuery($sql, $params);

        $this->id = Database::getInstance()->getConnection()->lastInsertId();

    }

    public function update(): void
    {
        $sql = "UPDATE actors SET movie_id = :movie_id, actor_name = :actor_name WHERE id = :id";
        $params = [
            ':id' => $this->id,
            ':movie_id' => $this->movieId,
            ':actor_name' => $this->actorName
        ];

        DatabaseHelper::executeQuery($sql, $params);
    }

    public function delete(): void
    {
        $sql = "DELETE FROM actors WHERE id = :id";
        $params = [':id' => $this->id];

        DatabaseHelper::executeQuery($sql, $params);
    }

    public static function getActorsByMovieId(int $movieId): array
    {
        $sql = "SELECT * FROM actors WHERE movie_id = :movie_id";
        $params = [':movie_id' => $movieId];

        return DatabaseHelper::executeFetchAll($sql, $params, 'App\Models\Actor');
    }

    public static function getById(int $id): ?Question
    {
        $sql = "SELECT * FROM actors WHERE id = :id";
        $params = [':id' => $id];

        return DatabaseHelper::executeFetchObject($sql, $params, 'App\Models\Actor');
    }

    /**
     * @throws Exception
     */
    public function getActors(): array {
        return self::getActorsByMovieId($this->getId());
    }
}
