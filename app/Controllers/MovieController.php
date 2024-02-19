<?php

namespace App\Controllers;

use Exception;
use App\Models\User;
use App\Models\Actor;
use App\Models\Movie;
use App\Exceptions\NotFoundObjectException;
use App\Services\MovieService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class MovieController
{
    public function createMovieForm(RouteCollection $routes, ?Request $request): void
    {
        require_once APP_ROOT . '/views/create_movie.php';
    }

    /**
     * @throws Exception
     */
    public function createMovie(RouteCollection $routes, Request $request): void
    {

        $title = $request->get('title');
        $format = $request->get('format');
        $realiseYear = $request->get('realise_year');
        $currentUser = User::getCurrentUser();

        $movie = new Movie();
        $movie->setUserId($currentUser->getId());
        $movie->setTitle($title);
        $movie->setFormat($format);
        $movie->setReleaseYear($realiseYear);

        $movie->create();

        $this->processActors($request, function($actor) use ($movie, $request) {
            $actor->setMovieId($movie->getId());
            $actor->create();
        });

        header("Location: /profile/list_movies");
        exit();
    }

    private function processActors(Request $request, $callback): void
    {
        $actorNames = $request->get('actor_text');

        if ($actorNames && is_array($actorNames)) {
            foreach ($actorNames as $actorIndex => $actorName) {
                $actor = new Actor();
                $actor->setActorName($actorName);

                $callback($actor, $actorIndex);
            }
        }
    }

    /**
     * @throws NotFoundObjectException
     */
    public function editMovieForm(RouteCollection $routes, ?Request $request, ?int $id): void
    {
        $movie = Movie::getById($id);
        
        if ($movie) {
            require_once APP_ROOT . '/views/edit_movie.php';
        }
    }

    /**
     * @throws Exception
     */
    public function editMovie(RouteCollection $routes, Request $request, ?int $id): void
    {
        $title = $request->get('title');
        $format = $request->get('format');
        $realseYear = $request->get('realise_year');
        $actors = $request->get('actor_text');
        $deletedActors = $request->get('deleted_actors');

        if (isset($deletedActors)) {
            foreach ($deletedActors as $deleteActor => $deletedActorId) {
                $deleteActor = Actor::getById($deletedActorId);
                $deleteActor?->delete();
            }
        }

        $movieService = new MovieService();
        $movieService->processMovieData($id, $title, $format, $realseYear, $actors);

        header("Location: /profile/list_movies", true, 200);
        exit();
    }

    /**
     * @throws Exception
     */
    public function deleteMovie(RouteCollection $routes, Request $request, int $id): void
    {
        $actors = Actor::getActorsByMovieId($id);
        
        foreach ($actors as $actor) {
            $actor->delete();
        }

        $movie = Movie::getById($id);

        if ($movie) {
            $movie->delete();

            header("Location: /profile/list_movies");
            exit();
        }
    }

    public function filterMovies(RouteCollection $routes, Request $request): void
    {
        $title = $request->get('title');
        $status = $request->get('actor_text');

        $sql = "SELECT * FROM movies WHERE 1";

        if ($title) {
            $sql .= " AND title LIKE '%$title%'";
        }

        if ($status) {
            $sql .= " AND status = '$status'";
        }

        $movies = Movie::getMoviesByCustomQuery($sql);

        foreach ($movies as $movie) {
            $movie->actor = Actor::getActorsByMovieId($movie->getId());

        }

        require_once APP_ROOT . '/views/filtered_movies.php';
    }
}
