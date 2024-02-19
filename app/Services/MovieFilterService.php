<?php

namespace App\Services;

use App\Models\Movie;

class MovieFilterService
{
    public function filterMovies(?string $title, ?string $actor): array
    {
        $sql = "SELECT * FROM movies WHERE 1";

        if ($title) {
            $sql .= " AND title LIKE '%$title%'";
        }

        if ($actor) {
            $sql .= " AND status = '$actor'";
        }

        return Movie::getMoviesByCustomQuery($sql);
    }
}
