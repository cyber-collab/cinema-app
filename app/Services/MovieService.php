<?php

namespace App\Services;

use App\Models\Movie;
use App\Exceptions\NotFoundObjectException;
use App\Models\Actor;

class MovieService
{
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

}

