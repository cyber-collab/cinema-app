<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\Actor;
use App\Helpers\Database;
use App\Exceptions\DatabaseException;

class ActorRepository
{
    public function create(int $movieId, string $actorName): int
    {
        try {
            $sql = "INSERT INTO actors (movie_id, name) VALUES (:movie_id, :name)";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $actorName, PDO::PARAM_STR);
            $stmt->execute();

            return Database::getInstance()->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            throw new DatabaseException("Error creating actor: " . $e->getMessage());
        }
    }

    public function update(int $id, int $movieId, string $actorName): void
    {
        try {
            $sql = "UPDATE actors SET movie_id = :movie_id, name = :name WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $actorName, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new DatabaseException("Error updating actor: " . $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $sql = "DELETE FROM actors WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new DatabaseException("Error deleting actor: " . $e->getMessage());
        }
    }

    public function getActorsByMovieId(int $movieId): ?array
    {
        try {
            $sql = "SELECT * FROM actors WHERE movie_id = :movie_id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Actor::class);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching actors by movie ID: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?Actor
    {
        try {
            $sql = "SELECT * FROM actors WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Actor::class);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching actor by ID: " . $e->getMessage());
        }
    }
}
