<?php

$pageTitle = "Créer mon quiz";

require_once(__DIR__ . "/../header.php");

$quizData = [];
$questionsData = [];

// Initialize session variables for quizData and questionsData if not already set
if (!isset($_SESSION["quizData"]) || !isset($_SESSION["questionsData"])) {
    $_SESSION["quizData"] = [];
    $_SESSION["questionsData"] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_question"])) {
    // Store quiz information in the session
    $_SESSION["quizData"] = [
        "name" => $_POST["name"],
        "image" => $_POST["image"],
        "description" => $_POST["description"],
    ];

    // Preserve existing questions before adding a new one
    if (!empty($_POST["questions"])) {
        foreach ($_POST["questions"] as $index => $q) {
            $_SESSION["questionsData"][$index] = [
                "question" => htmlspecialchars($q["text"]),
                "answers" => array_map(fn($answer) => htmlspecialchars($answer), $q["answers"]),
                "correctAnswer" => $q["correctAnswer"] ?? null
            ];
        }
    }

    // Append a new empty question to the session
    $_SESSION["questionsData"][] = [
        "question" => "",
        "answers" => ["", "", "", ""],
        "correctAnswer" => NULL
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_quiz"])) {
    // Sanitize and store quiz information from POST data
    $quizData["name"] = htmlspecialchars(trim($_POST["name"]));
    $quizData["image"] = filter_var(trim($_POST["image"]), FILTER_SANITIZE_URL);
        if (!filter_var($quizData["image"], FILTER_VALIDATE_URL)) {
            $_SESSION["errorMessage"] = "Le lien de l'image n'est pas écrit dans un format valide.";
        }
    $quizData["description"] = htmlspecialchars(trim($_POST["description"]));

    // Process each question stored in the session
    foreach ($_SESSION["questionsData"] as $index => $q) {
        $correctAnswer = isset($_POST["questions"][$index]["correctAnswer"]) 
        ? $_POST["questions"][$index]["correctAnswer"] : null;
        $questionsData[] = [
            "question" => htmlspecialchars(trim($_POST["questions"][$index]["text"])),
            "answers" => isset($_POST["questions"][$index]["answers"]) ? 
                array_map(fn($answer) => htmlspecialchars($answer), 
                $_POST["questions"][$index]["answers"]) : [],
            "correctAnswer" => $correctAnswer
        ];
    }    

    // Instantiate the QuizController and attempt to create the quiz
    $quizController = new QuizController;
    $result = $quizController->createQuiz($quizData, $questionsData);
}

?>

<main>
    <!-- Display error message if any errors exist -->
    <?php if (isset($_SESSION["errorMessage"])) : ?>
        <p class="message error"><?php echo $_SESSION["errorMessage"] ; ?></p>
        <?php unset($_SESSION["errorMessage"]); ?>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <h2>Créer mon quiz !</h2>
        
        <section class="formBody">
            <article class="formItem">
                <label for="name">Nom du quiz:</label>
                <input type="text" id="name" name="name" placeholder="Nom du quiz" required 
                value="<?= $_SESSION["quizData"]["name"] ?? "" ?>">
            </article>

            <article class="formItem">
                <label for="image">URL de l'image du quiz:</label>
                <input type="url" id="image" name="image" placeholder="URL de l'image du quiz" 
                value="<?= $_SESSION["quizData"]["image"] ?? "" ?>">
            </article>

            <article class="formItem">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="Description" required 
                value="<?= $_SESSION["quizData"]["description"] ?? "" ?>">
            </article>

            <!-- Loop through each question stored in session and generate form fields -->
            <?php foreach ($_SESSION["questionsData"] as $index => $question) : ?>
                <article class="formItem">
                    <label for="question<?= $index ?>">Question <?= $index + 1 ?>:</label>
                    <input type="text" id="question<?= $index ?>" name="questions[<?= $index ?>][text]" 
                    value="<?= htmlspecialchars_decode(htmlspecialchars($question["question"])) ?>" 
                    placeholder="Question <?= $index + 1 ?>" required>
                </article>

                <!-- Loop for answer inputs -->
                <article class="answer">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <article class="form-answer">
                            <label for="answer<?= $index ?>_<?= $i ?>">Réponse <?= $i ?>:</label>
                            <input type="text" id="answer<?= $index ?>_<?= $i ?>" 
                                name="questions[<?= $index ?>][answers][]" 
                                value="<?= isset($question["answers"][$i - 1]) && 
                                is_string($question["answers"][$i - 1]) ? 
                                htmlspecialchars_decode(htmlspecialchars($question["answers"][$i - 1])) : "" ?>" 
                                placeholder="Réponse <?= $i ?>" required>
                        </article>
                    <?php endfor; ?>
                </article>

                <!-- Radio buttons for selecting the correct answer -->
                <article class="form-correctAnswer">
                    <label>Bonne réponse:</label>
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <input type="radio" id="correctAnswer<?= $index ?>_<?= $i ?>" 
                        name="questions[<?= $index ?>][correctAnswer]" value="<?= $i ?>" 
                        <?= ($i == $question["correctAnswer"]) ? "checked" : "" ?> required>
                        <label for="correctAnswer<?= $index ?>_<?= $i ?>"><?= $i ?></label>
                    <?php endfor; ?>
                </article>
            <?php endforeach; ?>

            <!-- Hidden input for user ID -->
            <input type="hidden" id="id" name="id" value="<?php echo $_SESSION["id"] ?>">

            <article class="menuButton">
                <button type="submit" name="add_question" class="button hover"><i 
                class="fa-solid fa-plus"></i></button>

                <button type="submit" name="create_quiz" class="button jump">Créer mon quiz</button>
            </article>
        </section>
    </form>
</main>

<?php require_once(__DIR__ . "/../footer.php"); ?>