<?php

namespace App\Controllers;

use Exception;
use App\Models\User;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Answer;
use App\Models\Survey;
use App\Models\Question;
use App\Services\SurveyService;
use JetBrains\PhpStorm\NoReturn;
use App\Exceptions\NotFoundObjectException;
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
        $currentUser = User::getCurrentUser();

        $movie = new Movie();
        $movie->setUserId($currentUser->getId());
        $movie->setTitle($title);
        $movie->setFormat($format);
        $movie->create();
        // $this->processQuestions($request, function($question, $questionIndex) use ($movie, $request) {
        //     $question->setSurveyId($movie->getId());
        //     $question->create();

        //     $this->processAnswers(
        //     $request, function($answerText, $questionIndex) use ($question) {
        //         $answer = new Answer();
        //         $answer->setQuestionId($question->getId());
        //         $answer->setAnswerText($answerText);
        //         $answer->create();
        //     });
        // });

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
    public function editSurveyForm(RouteCollection $routes, ?Request $request, ?int $id): void
    {
        $survey = Survey::getById($id);

        if ($survey) {
            require_once APP_ROOT . '/views/edit_survey.php';
        }
    }

    /**
     * @throws Exception
     */
    public function editSurvey(RouteCollection $routes, Request $request, ?int $id): void
    {
        $title = $request->get('title');
        $status = $request->get('status');
        $questionTexts = $request->get('question_text');
        $answerTexts = $request->get('answer_text');

        $surveyService = new SurveyService();
        $surveyService->processSurveyData($id, $title, $status, $questionTexts, $answerTexts);

        $deletedQuestions = $request->get('deleted_questions');
        if (isset($deletedQuestions)) {
            foreach ($deletedQuestions as $deletedQuestionId) {
                $answers = Answer::getAnswersByQuestionId($deletedQuestionId);
                foreach ($answers as $answer) {
                    $answer->delete();
                }

                $deletedQuestion = Question::getById($deletedQuestionId);
                $deletedQuestion?->delete();
            }
        }

        $deletedAnswers = $request->get('deleted_answers');
        if (isset($deletedAnswers)) {
            foreach ($deletedAnswers as $deletedAnswerId) {
                $deletedAnswer = Answer::getById($deletedAnswerId);
                $deletedAnswer?->delete();
            }
        }

        header("Location: /profile/list_surveys", true, 200);
        exit();
    }

    /**
     * @throws Exception
     */
    // public function deleteMovie(RouteCollection $routes, Request $request, int $id): void
    // {
    //     $actors = Actor::getActorsByMovieId($id);
    //     foreach ($actors as $actor) {

    //         $question->delete();
    //     }

    //     $survey = Survey::getById($id);

    //     if ($survey) {
    //         $survey->delete();

    //         header("Location: /profile/list_surveys");
    //         exit();
    //     }
    // }

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

        $surveys = Survey::getSurveysByCustomQuery($sql);

        foreach ($surveys as $survey) {
            $survey->questions = Question::getQuestionsBySurveyId($survey->getId());

            foreach ($survey->questions as $question) {
                $question->options = Answer::getAnswersByQuestionId($question->getId());
            }
        }

        require_once APP_ROOT . '/views/filtered_surveys.php';
    }

    /**
     * @throws Exception
     */
    // public function recordVote(RouteCollection $routes, Request $request): void
    // {
    //     $questionId = $request->get('question_id');
    //     $answerId = $request->get('answer_id');

    //     $question = Question::getById($questionId);
    //     $answer = Answer::getById($answerId);

    //     if ($question === null || $answer === null) {
    //         echo "Invalid question or answer";
    //         exit();
    //     }

    //     Answer::recordVote($questionId, $answerId);

    //     header("Location: /all-surveys");
    //     exit();
    // }
}
