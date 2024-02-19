<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

// Routes system
$routes = new RouteCollection();
// routing for user
$routes->add('homepage', new Route(constant('URL_SUBFOLDER') . '/', ['controller' => 'PageController', 'method'=>'index'], []));
$routes->add('user', new Route(constant('URL_SUBFOLDER') . '/user/{id}', ['controller' => 'UserController', 'method'=>'showAction'], ['id' => '[0-9]+']));
$routes->add('register', new Route(constant('URL_SUBFOLDER') . '/register', ['controller' => 'RegistrationController', 'method'=>'showRegistrationForm'], []));
$routes->add('register_new', new Route(constant('URL_SUBFOLDER') . '/register/new', ['controller' => 'RegistrationController', 'method'=>'register'], []));
$routes->add('profile', new Route(constant('URL_SUBFOLDER') . '/profile', ['controller' => 'ProfileController', 'method'=>'showProfileForm'], []));
$routes->add('login', new Route(constant('URL_SUBFOLDER') . '/login', ['controller' => 'LoginController', 'method'=>'showLoginForm'], []));
$routes->add('login_authenticate', new Route(constant('URL_SUBFOLDER') . '/login/authenticate', ['controller' => 'LoginController', 'method' => 'authenticate'], []));
$routes->add('update_user', new Route(constant('URL_SUBFOLDER') . '/user/update/{id}', ['controller' => 'UserController', 'method' => 'update'], ['id' => '\d+']));
$routes->add('logout', new Route(constant('URL_SUBFOLDER') . '/logout', ['controller' => 'ProfileController', 'method' => 'logout'], []));

//routing for movies
$routes->add('movie', new Route(constant('URL_SUBFOLDER') . '/movie', ['controller' => 'MovieController', 'method' => 'createMovieForm'], []));
$routes->add('movie/new', new Route(constant('URL_SUBFOLDER') . '/movie/new', ['controller' => 'MovieController', 'method' => 'createMovie'], []));
$routes->add('all_movies', new Route(constant('URL_SUBFOLDER') . '/all-movies', ['controller' => 'PageController', 'method' => 'getAllMovies'], []));
$routes->add('list_movies', new Route(constant('URL_SUBFOLDER') . '/profile/list_movies', ['controller' => 'ProfileController', 'method' => 'listMovies'], []));
$routes->add('edit_movie', new Route(constant('URL_SUBFOLDER') . '/movie/edit/{id}', ['controller' => 'MovieController', 'method' => 'editMovieForm'], ['id' => '\d+']));
$routes->add('update_movie', new Route(constant('URL_SUBFOLDER') . '/movie/update/{id}', ['controller' => 'MovieController', 'method' => 'editMovie'], ['id' => '\d+']));
$routes->add('delete_movie', new Route(constant('URL_SUBFOLDER') . '/movie/delete/{id}', ['controller' => 'MovieController', 'method' => 'deleteMovie'], ['id' => '\d+']));
$routes->add('filter_movies', new Route(constant('URL_SUBFOLDER') . '/filter-movies', ['controller' => 'MovieController', 'method' => 'filterMovies'], []));