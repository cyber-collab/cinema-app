<?php

namespace App\Controllers;

use Exception;
use App\Models\User;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Answer;
use App\Models\Survey;
use App\Models\Question;
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

        header("Location: /profile/list_movies");
        exit();
    }


    private function processActors(Request $request, $callback): void
    {
        $answerTexts = $request->get('answer_text');

        if ($answerTexts && is_array($answerTexts)) {
            foreach ($answerTexts as $questionIndex => $answers) {
                foreach ($answers as $answerText) {
                    $callback($answerText, $questionIndex);
                }
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

        $movieService = new MovieService();
        $movieService->processMovieData($id, $title, $format, $realseYear);

        header("Location: /profile/list_movies", true, 200);
        exit();
    }

    /**
     * @throws Exception
     */
    public function deleteMovie(RouteCollection $routes, Request $request, int $id): void
    {
        // $actors = Actor::getActorsByMovieId($id);
        // foreach ($actors as $actor) {

        //     $question->delete();
        // }

        $movie = Movie::getById($id);

        if ($movie) {
            $movie->delete();

            header("Location: /profile/list_movies");
            exit();
        }
    }

    public function filterSurveys(RouteCollection $routes, Request $request): void
    {
        $title = $request->get('title');
        $status = $request->get('status');
        $publishedDate = $request->get('created_at');

        $sql = "SELECT * FROM surveys WHERE 1";

        if ($title) {
            $sql .= " AND title LIKE '%$title%'";
        }

        if ($status) {
            $sql .= " AND status = '$status'";
        }

        if ($publishedDate) {
            $sql .= " AND DATE(created_at) = '$publishedDate'";
        }

        // $surveys = Survey::getSurveysByCustomQuery($sql);

        // foreach ($surveys as $survey) {
            // $survey->questions = Question::getQuestionsBySurveyId($survey->getId());

            // foreach ($survey->questions as $question) {
                // $question->options = Answer::getAnswersByQuestionId($question->getId());
            // }
        // }

        require_once APP_ROOT . '/views/filtered_surveys.php';
    }
}
