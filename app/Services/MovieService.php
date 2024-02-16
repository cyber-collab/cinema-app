<?php

namespace App\Services;

use App\Models\Movie;
use App\Exceptions\NotFoundObjectException;

class MovieService
{
    /**
     * @throws NotFoundObjectException
     */
    public function processMovieData(int $id, string $title, string $format, string $realseYear): void
    {
        $movies = Movie::getById($id);

        if ($movies) {
            $movies->setTitle($title);
            $movies->setFormat($format);
            $movies->setReleaseYear($realseYear);
            $movies->update();
        }
    }

    public static function processMoveis(array $movies): void
    {
         foreach ($movies as $movie)
         {
            $movie->setReleaseYear($movie->release_year);
         }
    }
}

