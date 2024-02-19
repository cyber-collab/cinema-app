<?php

namespace App\Services;

use App\Models\Actor;

class ActorService
{
    public function getActorsByMovieId(int $movieId): array
    {
        return Actor::getActorsByMovieId($movieId);
    }
}
