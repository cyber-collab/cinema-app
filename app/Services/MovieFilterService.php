<?php

namespace App\Services;

use App\Models\Movie;

class MovieFilterService
{
    public function filterMovies(?string $title, ?string $actor): array
    {
        $sql = "SELECT movies.* FROM movies
            JOIN actors ON movies.id = actors.movie_id
            WHERE 1 ";

        if ($title) {
            $sql .= "AND movies.title LIKE '%$title%' ";
        }

        if ($actor) {
            $sql .= "AND actors.name LIKE '%$actor%' ";
        }

        $sql .= "GROUP BY movies.id";

        $sql .= " ORDER BY movies.title";

        return Movie::getMoviesByCustomQuery($sql);
    }

}
