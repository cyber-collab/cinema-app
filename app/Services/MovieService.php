<?php

namespace App\Services;

use App\Models\Movie;
use App\Exceptions\NotFoundObjectException;

class MovieService
{
    /**
     * @throws NotFoundObjectException
     */
    public function processMovieData(int $id, string $title, string $format, array $questionData, array $answerData): void
    {
        $format = Movie::getById($id);

        if ($format) {
            $format->setTitle($title);
            $format->setFormat($format);
            $format->update();
        }
    }

    public static function processMoveis(array $movies): void
    {
        foreach ($movies as $movie) {
           
        }
    }
}

