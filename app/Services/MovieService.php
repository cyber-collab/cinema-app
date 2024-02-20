<?php

namespace App\Services;

use App\Models\User;
use App\Models\Actor;
use App\Models\Movie;
use App\Exceptions\UploadException;
use App\Exceptions\NotFoundObjectException;

class MovieService
{
    public function createMovie(string $title, string $format, string $realiseYear, array $actorNames): void
    {
        $currentUser = User::getCurrentUser();

        $movie = new Movie();
        if(isset($currentUser)) {
            $movie->setUserId($currentUser->getId());
        } else {
            $movie->setUserId(1);
        }

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

    public function editMovie(int $id, string $title, string $format, string $realiseYear, array $actorNames, ?array $deletedActorIds): void
    {
        $this->processMovieData($id, $title, $format, $realiseYear, $actorNames);

        ActorService::deleteActors($deletedActorIds);
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

    public static function processMoveis(array $movies): void
    {
        foreach ($movies as $movie) {
            $movie->setReleaseYear($movie->release_year);
            $movie->actors = Actor::getActorsByMovieId($movie->getId());
        }
    }

    public function uploadMovieDataFile(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new UploadException("Upload failed with error code {$file['error']}");
        }

        $data = file_get_contents($file['tmp_name']);
        $data = preg_replace('/^[\x{FEFF}\r\n]+/u', '', $data);

        $lines = explode("\n", $data);

        $title = '';
        $releaseYear = 1970;
        $format = '';
        $stars = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {

                if (!empty($title)) {
                    $this->createMovie($title, $format, $releaseYear, $stars);
                    $title = '';
                    $releaseYear = 1970;
                    $format = '';
                    $stars = [];
                }
            } else {
                list($key, $value) = explode(':', $line, 2);
                switch ($key) {
                    case 'Title':
                        $title = trim($value);
                        break;
                    case 'Release Year':
                        $releaseYear = (int) trim($value);
                        break;
                    case 'Format':
                        $format = trim($value);
                        break;
                    case 'Stars':
                        $stars = explode(', ', $value);
                        break;
                }
            }
        }

        if (!empty($title)) {
            $this->createMovie($title, $format, $releaseYear, $stars);
        }
    }
}
