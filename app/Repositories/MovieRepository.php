<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\Movie;
use App\Helpers\Database;
use App\Exceptions\DatabaseException;

class MovieRepository
{
    public function create(string $title, string $format, int $userId, ?string $releaseYear = null): int
    {
        try {
            $sql = "INSERT INTO movies (title, format, user_id, release_year, created_at) VALUES (:title, :format, :user_id, :release_year, NOW())";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':format', $format, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':release_year', $releaseYear, PDO::PARAM_STR);
            $stmt->execute();

            return Database::getInstance()->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            throw new DatabaseException("Error creating movie: " . $e->getMessage());
        }
    }

    public function update(int $id, string $title, string $format, string $releaseYear): void
    {
        try {
            $sql = "UPDATE movies SET title = :title, format = :format, release_year = :release_year WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':format', $format, PDO::PARAM_STR);
            $stmt->bindParam(':release_year', $releaseYear, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new DatabaseException("Error updating movie: " . $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $sql = "DELETE FROM movies WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new DatabaseException("Error deleting movie: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?Movie
    {
        try {
            $sql = "SELECT * FROM movies WHERE id = :id";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Movie::class);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching movie: " . $e->getMessage());
        }
    }

    public function getAllMovies(): ?array
    {
        try {
            $sql = "SELECT * FROM movies ORDER BY title ASC";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Movie::class);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching all movies: " . $e->getMessage());
        }
    }

    public function getMoviesByUserId(int $userId): ?array
    {
        try {
            $sql = "SELECT * FROM movies WHERE user_id = :userId ORDER BY title";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Movie::class);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching movies by user ID: " . $e->getMessage());
        }
    }

    public function getMoviesByCustomQuery(string $sql): ?array
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Movie::class);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching movies by custom query: " . $e->getMessage());
        }
    }
}
