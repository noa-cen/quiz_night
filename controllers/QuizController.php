<?php
require_once(__DIR__ . "/../classes/Autoloader.php"); 

class QuizController extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // createQuiz: Creates a new quiz along with its questions and answers
    public function createQuiz($quizData, $questionsData)
    {
        $quiz = new Quiz;
        $result = $quiz->create($quizData);

        if ($result !== true && isset($_SESSION["errorMessage"])) {
            return false;
        }

        // Instantiate the Question model and create questions for the quiz; retrieve question IDs
        $question = new Question;
        $questionIds = $question->create($quizData, $questionsData);

        if (!is_array($questionIds)) {
            return false;
        }

        // Instantiate the Answer model and create answers associated with the questions
        $answer = new Answer;
        $result = $answer->create($questionsData, $questionIds);

        if ($result === true && !isset($_SESSION["errorMessage"])) {
            $_SESSION["successMessage"] = "Votre quiz a été créé avec succès !";
            // Clear session data for quiz and questions after processing
            unset($_SESSION["quizData"]);
            unset($_SESSION["questionsData"]);
            header("Location: ../dashboard.php");
            exit();
        } else {
            return false;
        }
    }

    // startQuiz: Initializes the quiz by setting session variables and retrieving the quiz and its questions
    public function startQuiz($quizId) {
        $_SESSION["quiz_id"] = $quizId;
        $_SESSION["current_question"] = 0;
        $_SESSION["selected_answer"] = null;
        $_SESSION["correct_answers"] = [];
    
        // Retrieve quiz details and questions
        $quiz = new Quiz;
        $questions = (new Question)->play();
        
        return [$quiz, $questions];
    }
    
    // validateAnswer: Validates the user's answer for a given question
    public function validateAnswer($postData, $questionId) {
        $userAnswer = $postData["question_" . $questionId] ?? null;
        $correctAnswers = (new Answer)->getCorrectAnswers($questionId);
        $isCorrect = in_array($userAnswer, array_column($correctAnswers, "id"));
    
        // If the answer is correct, increment the user's score stored in the session
        if ($isCorrect) {
            $_SESSION["score"] = ($_SESSION["score"] ?? 0) + 1;
        }
    
        // Return a string indicating whether the answer is correct or incorrect
        return $isCorrect ? "correct" : "incorrect";
    }
    
    public function nextQuestion() {
        $_SESSION["current_question"]++;
        $_SESSION["selected_answer"] = null;
    }    

    // editQuiz: Edits an existing quiz, updating its name, image, description, questions, and answers
    public function editQuiz($quiz_id, $newName, $newImage, $newDescription, $newQuestions, $oldAnswers)
    {
        // Update quiz details using the Quiz model
        $quiz = new Quiz;
        $editQuiz = $quiz->edit($quiz_id, $newName, $newImage, $newDescription);

        // Update quiz questions using the Question model
        $question = new Question;
        $editQuestion = $question->edit($quiz_id, $newQuestions);

        // Update quiz answers using the Answer model
        $answer = new Answer;
        $editAnswer = $answer->edit($newQuestions, $oldAnswers);

        if ($editQuiz === true && $editQuestion === true && $editAnswer === true) {
            $_SESSION["successMessage"] = "Votre quiz a été modifié avec succès !";
            // Clear session data for quiz and questions after processing
            unset($_SESSION["quizData"]);
            unset($_SESSION["questionsData"]);
            header("Location: ../dashboard.php");
            exit();
        } else {
            return "Erreur lors de la modification du nom d'utilisateur.";
        }
    }
}
