<?php

namespace App\Controllers;

use App\Exceptions\NotFoundObjectException;
use App\Models\Movie;
use App\Models\User;
use App\Services\MovieService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class ProfileController
{
    /**
     * @throws NotFoundObjectException
     */
    public function showProfileForm(RouteCollection $routes, ?Request $request): void
    {
        $currentUser = User::getCurrentUser();

        if ($currentUser !== null) {
            require_once APP_ROOT . '/views/profile.php';
        } else {
            echo "User not authenticated";
        }
    }

    /**
     * @throws NotFoundObjectException
     */
    public function listMovies(RouteCollection $routes, ?Request $request): void
    {
        $currentUser = User::getCurrentUser();
        $movies = Movie::getMoviesByUserId($currentUser->getId());
        MovieService::processMoveis($movies);

        require_once APP_ROOT . '/views/list_movies.php';
    }

    public function logout(RouteCollection $routes, ?Request $request): void
    {
        User::logout();

        echo "You have been logged out.";
    }
}
