<?php
ob_start(); // Start output buffering

$pageTitle = "Modifier mon quiz"; 
require_once(__DIR__ . "/../header.php");  
require_once(__DIR__ . "/../../classes/Autoloader.php");
require_once(__DIR__ . "/deleteQuiz.php"); 

// If a quiz_id is provided via GET, store it in the session and set a cookie for it
if (isset($_GET["quiz_id"])) {
    $_SESSION["quiz_id"] = $_GET["quiz_id"];
    setcookie("quiz_id", $_GET["quiz_id"], time() + 3600, "/");
}

// If quiz_id is not in the session but exists in a cookie, use the cookie value
if (!isset($_SESSION["quiz_id"]) && isset($_COOKIE["quiz_id"])) {
    $_SESSION["quiz_id"] = $_COOKIE["quiz_id"];
}

// If no quiz_id is available, terminate the script with an error message
if (!isset($_SESSION["quiz_id"])) {
    die("Erreur : Aucun quiz sélectionné.");
}

// Retrieve the quiz details based on the quiz_id stored in the session
$quiz = (new Quiz)->play($_SESSION["quiz_id"]);

// Retrieve the questions associated with the quiz
$questions = (new Question)->play();

$answers = [];
// For each question, retrieve its corresponding answers
foreach ($questions as $question) {
    $answers[$question["id"]] = (new Answer)->play($question["id"]);
}

// Process the form submission for editing the quiz
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_quiz"])) {
    $quiz_id = $_SESSION["quiz_id"];
    $newName = trim($_POST["name"]);
    $newImage = trim($_POST["image"]);
    $newDescription = trim($_POST["description"]);

    $newQuestions = [];
    // Loop through $_POST["questions"] where the keys are the IDs of the questions
    foreach ($_POST["questions"] as $qid => $questionData) {
        $newQuestions[$qid] = [
            "id" => $qid,
            "text" => trim($questionData["text"]),
            "answers" => [],
            "correct" => $questionData["correct"] ?? null
        ];

        // Retrieve the answers for this question (the keys of $_POST["answers"] are also the question IDs)
        if (isset($_POST["answers"][$qid])) {
            foreach ($_POST["answers"][$qid] as $answerIndex => $answerText) {
                $newQuestions[$qid]["answers"][$answerIndex] = trim($answerText);
            }
        }
    }

    // Instantiate the QuizController and call the editQuiz method, passing the original answers as well
    $quizController = new QuizController;
    $edit = $quizController->editQuiz($quiz_id, $newName, $newImage, $newDescription, $newQuestions, $answers);
}

?>

<main>
    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"] ; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <h2>Modifier mon quiz !</h2>

        <section class="formBody">
            <article class="formItem">
                <label for="name">Nom du quiz:</label>
                <input type="text" id="name" name="name" required value="<?php echo $quiz["name"] ?>">
            </article>

            <article class="formItem">
                <label for="image">URL de l'image du quiz:</label>
                <input type="url" id="image" name="image" value="<?php echo $quiz["image"] ?>">
            </article>

            <article class="formItem">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required 
                value="<?php echo $quiz["description"] ?>">
            </article>

            <?php foreach ($questions as $index => $question) : ?>
                <!-- Display each question with its text input -->
                <article class="formItem">
                    <label for="question_<?php echo $question["id"]; ?>">Question <?php echo $index + 1; ?> :</label>
                    <!-- Use the question ID as the key -->
                    <input type="text" id="question_<?php echo $question["id"]; ?>" 
                        name="questions[<?php echo $question["id"]; ?>][text]" 
                        value="<?php echo $question["question_text"]; ?>" required>
                </article>
                
                <?php $questionAnswers = $answers[$question["id"]]; ?>

                <!-- Display answer inputs for the question -->
                <article class="answer">
                    <?php foreach ($questionAnswers as $i => $answer) : ?>
                        <article class="form-answer">
                            <label for="answer_<?php echo $question["id"] . "_" . $i; ?>">Réponse 
                                <?php echo $i + 1; ?> :</label>
                            <input type="text" id="answer_<?php echo $question["id"] . "_" . $i; ?>" 
                                name="answers[<?php echo $question["id"]; ?>][<?php echo $i; ?>]" 
                                value="<?php echo $answer["answer_text"]; ?>" required>
                        </article>
                    <?php endforeach; ?>
                </article>

                <!-- Display radio buttons for selecting the correct answer -->
                <article class="form-correctAnswer">
                <label>Bonne réponse:</label>
                <?php 
                    $correctAnswer = null;
                    // Determine the correct answer for the question
                    foreach ($questionAnswers as $answer) {
                        if ($answer["is_correct"] == 1) {
                            $correctAnswer = $answer["id"];
                            break;
                        }
                    }                    
                    $i = 0;
                    // Loop through each answer to display the radio button
                    foreach ($questionAnswers as $answer) :
                        $isChecked = ($correctAnswer == $answer["id"]) ? "checked" : "";?>
                    <input type="radio" id="correct_<?php echo $question["id"] . "_" . $answer["id"]; ?>" 
                        name="questions[<?php echo $question["id"]; ?>][correct]" 
                        value="<?php echo $answer["id"]; ?>" 
                        <?php echo $isChecked; ?> required>
                    <label for="correct_<?php echo $question["id"] . "_" . $answer["id"]; ?>">
                        <?php echo $i + 1; ?>
                    </label>
                <?php $i++; endforeach; ?>
                </article>
            <?php endforeach; ?>

        <input type="hidden" id="id" name="id" value="<?php echo $_SESSION["id"] ?>">

        <input type="submit" value="Modifier mon quiz" name="edit_quiz" class="button jump">
        </section>
    </form>
</main>

<!-- End output buffering and flush the output -->
<?php ob_end_flush(); ?>

<?php require_once(__DIR__ . "/../footer.php"); ?>
