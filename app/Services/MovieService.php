<?php

namespace App\Services;

use App\Models\User;
use App\Models\Actor;
use App\Models\Movie;
use App\Exceptions\NotFoundObjectException;

class MovieService
{
    private function editProcessActors(int $movieId, array $actorData): array
    {
        $newActorsIds = [];

        foreach ($actorData as $actorId => $actorName) {
            if (str_starts_with($actorId, 'new_')) {
                $newActor = new Actor();
                $newActor->setActorName($actorName);
                $newActor->setMovieId($movieId);
                $newActor->create();
                $newActorsIds[] = $newActor->getId();
            } else {
                $actor = Actor::getById($actorId);
                if ($actor) {
                    $actor->setActorName($actorName);
                    $actor->setMovieId($movieId);
                    $actor->update();
                }
            }
        }

        return $newActorsIds;
    }

    public function createMovie(string $title, string $format, string $realiseYear, array $actorNames): void
    {
        $currentUser = User::getCurrentUser();

        $movie = new Movie();
        $movie->setUserId($currentUser->getId());
        $movie->setTitle($title);
        $movie->setFormat($format);
        $movie->setReleaseYear($realiseYear);
        $movie->create();

        foreach ($actorNames as $actorName) {
            $actor = new Actor();
            $actor->setActorName($actorName);
            $actor->setMovieId($movie->getId());
            $actor->create();
        }
    }

    public function editMovie(int $id, string $title, string $format, string $realiseYear, array $actorNames, ?array $deletedActorIds): void
    {
        $movie = Movie::getById($id);

        if ($movie) {
            $movie->setTitle($title);
            $movie->setFormat($format);
            $movie->setReleaseYear($realiseYear);
            $movie->update();

            if(empty($deletedActorId)){
                return;
            }

            foreach ($deletedActorIds as $deletedActorId) {
                $actor = Actor::getById($deletedActorId);
                if ($actor) {
                    $actor->delete();
                }
            }

            foreach ($actorNames as $actorName) {
                $actor = new Actor();
                $actor->setActorName($actorName);
                $actor->setMovieId($id);
                $actor->create();
            }
        }
    }

    public function deleteMovie(int $id): void
    {
        $movie = Movie::getById($id);

        if ($movie) {
            $actors = Actor::getActorsByMovieId($id);
            foreach ($actors as $actor) {
                $actor->delete();
            }
            $movie->delete();
        }
    }

    /**
     * @throws NotFoundObjectException
     */
    public function processMovieData(int $id, string $title, string $format, string $realseYear, array $actors): void
    {
        $movies = Movie::getById($id);

        if ($movies) {
            $movies->setTitle($title);
            $movies->setFormat($format);
            $movies->setReleaseYear($realseYear);

            $this->editProcessActors($id, $actors);
            $movies->update();
        }
    }

    public static function processMoveis(array $movies): void
    {
        foreach ($movies as $movie)
        {
            $movie->setReleaseYear($movie->release_year);
            $movie->actors = Actor::getActorsByMovieId($movie->getId());
        }
    }
}

