<?php

namespace App\Services;

use App\Models\Actor;

class ActorService
{
    public function getActorsByMovieId(int $movieId): array
    {
        return Actor::getActorsByMovieId($movieId);
    }

    public static function deleteActors(array $deletedActorIds): void
    {
        if (!empty($deletedActorIds)) {
            foreach ($deletedActorIds as $deletedActorId) {
                $actor = Actor::getById($deletedActorId);
                if ($actor) {
                    $actor->delete();
                }
            }
        }
    }
}
