<?php

$pageTitle = "Jouer !";
require_once(__DIR__ . "/../header.php"); 
require_once(__DIR__ . "/../../classes/Autoloader.php");

$controller = new QuizController;

// If a quiz_id is passed via GET, save it in the session and set a cookie
if (isset($_GET["quiz_id"])) {
    $_SESSION["quiz_id"] = $_GET["quiz_id"];
    setcookie("quiz_id", $_GET["quiz_id"], time() + 3600, "/");
}

// If quiz_id is not set in session but exists in a cookie, use the cookie value
if (!isset($_SESSION["quiz_id"]) && isset($_COOKIE["quiz_id"])) {
    $_SESSION["quiz_id"] = $_COOKIE["quiz_id"];
}

// If no quiz_id is available, terminate the script with an error message
if (!isset($_SESSION["quiz_id"])) {
    die("Erreur : Aucun quiz sélectionné.");
}

// Initialize quiz session variables if they are not already set
if (!isset($_SESSION["current_question"])) {
    $_SESSION["current_question"] = 0;
    $_SESSION["selected_answer"] = null;
    $_SESSION["correct_answers"] = [];
    $_SESSION["score"] = 0;
}

$currentQuestionIndex = $_SESSION["current_question"];
// Retrieve all questions for the quiz
$questions = (new Question)->play();
// Retrieve the quiz details using the quiz_id stored in the session
$quiz = (new Quiz)->play($_SESSION["quiz_id"]);

// If there is no question for the current index, then the quiz is over
$scoreController = new ScoreController;

if (!isset($questions[$currentQuestionIndex])) {
    $score = $_SESSION["score"];
    $_SESSION["successMessage"] = "Votre score final : " . $score . " sur " . $_SESSION["current_question"];
    $scoreController->addScore(); 
    // Clear quiz-related session variables
    unset($_SESSION["quiz_id"], $_SESSION["current_question"], $_SESSION["selected_answer"], 
    $_SESSION["correct_answers"], $_SESSION["score"]);
    header("Location: ../score/score.php");
    exit();
}

// Get the current question to be displayed
$currentQuestion = $questions[$currentQuestionIndex];

if (isset($_GET["timer_expired"]) && !isset($_SESSION["selected_answer"])) {
    $answersForValidation = (new Answer)->play($currentQuestion["id"]);
    $correctAnswer = null;
    foreach ($answersForValidation as $ans) {
        if (!empty($ans["is_correct"])) {
            $correctAnswer = $ans["id"];
            break;
        }
    }
    // Simulate that the user has “chosen” the correct answer
    $_SESSION["selected_answer"] = $correctAnswer;
    $_SESSION["correct_answers"][] = $correctAnswer;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["validate_answer"])) {
        // Retrieve the selected answer from POST data using the current question ID
        $selectedAnswer = $_POST["question_" . $currentQuestion["id"]] ?? null;
        // Save the selected answer in the session
        $_SESSION["selected_answer"] = $selectedAnswer;
        
        // Retrieve all answers for the current question
        $answersForValidation = (new Answer)->play($currentQuestion["id"]);
        
        // Validate the user's answer using the QuizController method
        $result = $controller->validateAnswer($_POST, $currentQuestion["id"]);
        
        if ($result === "correct") {
            // If the answer is correct, add the selected answer to the list of correct answers
            $_SESSION["correct_answers"][] = $selectedAnswer;
        } else {
            // If the answer is incorrect, search for the correct answer's ID
            foreach ($answersForValidation as $ans) {
                // Assume the "is_correct" field equals 1 for the correct answer
                if (!empty($ans["is_correct"])) {
                    $_SESSION["correct_answers"][] = $ans["id"];
                    break;
                }
            }
        }
    } elseif (isset($_POST["next_question"])) {
        // Increment the current question index
        $_SESSION["current_question"]++;
        // Reset the selected answer
        $_SESSION["selected_answer"] = null;
        // Redirect to the same page to load the next question
        header("Location: playQuiz.php");
        exit();
    }
}

// Retrieve the answers for the current question for display
$answers = (new Answer)->play($currentQuestion["id"]);
?>

<?php if (!isset($_SESSION["selected_answer"]) && !isset($_GET["timer_expired"])) : ?>
    <script src="/quiz_night/scripts/script.js" defer></script>
<?php endif; ?>

<main>
    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"]; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <section class="title">
            <h2><?php echo $quiz["name"]; ?></h2>

            <article class="clock">
                <span id="timer">00:20</span>
            </article>          
        </section>
        <section class="formBody">
            <article class="formItem">
                <h3>Question <?php echo $currentQuestionIndex + 1; ?> :</h3>
                <p><?php echo $currentQuestion["question_text"];?></p>
            </article>

            <!-- Display the answer options with styling for correct/incorrect responses -->
            <article class="playQuiz">
                <?php foreach ($answers as $response) : 
                    // Assign CSS classes:
                    // - If an answer has been submitted, show:
                    //    • "correct" for the correct answer (even if not selected)
                    //    • "incorrect" for the selected answer if it is wrong
                    $class = "";
                    if (isset($_SESSION["selected_answer"])) {
                        if ($_SESSION["selected_answer"] == $response["id"]) {
                            // For the selected answer, check if it is among the correct answers
                            $class = in_array($response["id"], $_SESSION["correct_answers"] ?? []) 
                            ? "correct" : "incorrect";
                        } else {
                            // For non-selected answers, display "correct" if it is the correct answer
                            $class = in_array($response["id"], $_SESSION["correct_answers"] ?? []) 
                            ? "correct" : "";
                        }
                    } ?>
                    <!-- Radio button for an answer option -->
                     <article class="playQuiz-answer">
                        <input type="radio" name="question_<?php echo $currentQuestion["id"] ?>" 
                            value="<?php echo $response["id"] ?>" id="answer_<?php echo $response["id"] ?>"
                            class="answer <?php echo $class; ?>" 
                            <?php echo isset($_SESSION["selected_answer"]) ? "disabled" : ""; ?>>

                        <!-- Label for the answer option -->
                        <label for="answer_<?php echo $response["id"] ?>" class="answer-label <?php echo $class; ?>">
                            <?php echo $response["answer_text"]; ?> </label>
                    </article>
                <?php endforeach; ?>
            </article>

            <!-- Display either the "Validate Answer" button or the "Next Question" button -->
            <?php if (!isset($_SESSION["selected_answer"])) : ?>
                <input type="submit" value="Valider la réponse" name="validate_answer" class="button hover">
            <?php else : ?>
                <input type="submit" value="Question suivante" name="next_question" class="button jump">
            <?php endif; ?>
        </section>
    </form>
</main>

<?php require_once(__DIR__ . "/../footer.php"); ?>