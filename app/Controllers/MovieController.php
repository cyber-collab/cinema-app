<?php

namespace App\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use App\Services\ActorService;
use App\Services\MovieFilterService;

class MovieController
{
    public function __construct(private readonly MovieService $movieService, private readonly ActorService $actorService, private readonly MovieFilterService $movieFilterService)
    {
    }

    public function createMovieForm(RouteCollection $routes, ?Request $request): Response
    {
        return new Response(require_once APP_ROOT . '/views/create_movie.php');
    }

    public function createMovie(RouteCollection $routes, Request $request): void
    {
        $this->movieService->createMovie($request->get('title'), $request->get('format'), $request->get('release_year'), $request->get('actor_text'));

        header("Location: /profile/list_movies");
        exit();
    }

    public function editMovieForm(RouteCollection $routes, ?Request $request, ?int $id): Response
    {
        $movie = Movie::getById($id);

        if (!$movie) {
            return new Response(404, Response::HTTP_NOT_FOUND, ['Location' => '/profile/list_movies']);
        }

        return new Response(require_once APP_ROOT . '/views/edit_movie.php');
    }

    public function editMovie(RouteCollection $routes, Request $request, ?int $id): void
    {
        $deletedActors = $request->get('deleted_actors');

        $this->movieService->editMovie($id, $request->get('title'), $request->get('format'), $request->get('release_year'), $request->get('actor_text'), $deletedActors);

        header("Location: /profile/list_movies");
        exit();
    }

    public function deleteMovie(RouteCollection $routes, Request $request, int $id): void
    {
        $this->movieService->deleteMovie($id);

        header("Location: /profile/list_movies");
        exit();
    }

    public function filterMovies(RouteCollection $routes, Request $request): Response
    {
        $title = $request->get('title');
        $actor = $request->get('actor');

        $movies = $this->movieFilterService->filterMovies($title, $actor);

        foreach ($movies as $movie) {
            $movie->actor = $this->actorService->getActorsByMovieId($movie->getId());
        }

        return new Response(require_once APP_ROOT . '/views/filtered_movies.php');
    }

    public function uploadMoviesFile(RouteCollection $routes, Request $request)
    {
        $uploadedFile = $_FILES['file'];

        $this->movieService->uploadMovieDataFile($uploadedFile);

        header("Location: /profile/list_movies");
        exit();
    }
}
