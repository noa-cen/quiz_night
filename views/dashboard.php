<?php

$pageTitle = "Accueil des quiz";
require_once(__DIR__ . "/header.php");

// Create a new Quiz object and retrieve all quizzes from the database
$quiz = new Quiz;
$quizzes = $quiz->display();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If the "playQuiz" button was pressed, redirect to the playQuiz page with the quiz_id parameter
    if (isset($_POST["playQuiz"])) {
        header("Location: quiz/playQuiz.php?quiz_id=" . $_POST["quiz_id"]);
        exit();
    }
    // If the "editQuiz" button was pressed, redirect to the editQuiz page with the quiz_id parameter
    if (isset($_POST["editQuiz"])) {
        header("Location: quiz/editQuiz.php?quiz_id=" . $_POST["quiz_id"]);
        exit();
    }
    // Clear the quiz_id session variable if no recognized action was performed
    $_SESSION["quiz_id"] = "";
}

?>

<main>
    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"] ; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <h2>Quiz disponibles !</h2>

    <section class="quizzes">
        <!-- If a user is logged in, display the option to create a new quiz -->
        <?php if (isset($_SESSION["id"])) : ?>
            <article class="quiz create">
                <img src="../assets/img/creeTonQuiz.webp" class="quiz-pic"
                alt="Picture of a questionmark and someone with a computer">
                <h3>Crée ton quiz</h3>
                <p>Choisis un thème et rédige tes questions.</p>

                <article class="menuButton">
                    <a href="/quiz_night/views/quiz/createQuiz.php" 
                    aria-label="Accéder à la creation de quiz" class="button jump action"><i 
                    class="fa-solid fa-plus"></i></a>
                </article>
            </article>
        <?php endif; ?>

        <!-- Loop through each quiz and display its information -->
        <?php foreach($quizzes as $quiz) : ?>
            <article class="quiz play">
                <img src="<?php echo $quiz["image"]; ?>" class="quiz-pic"
                alt="Picture of a questionmark and someone with a computer">
                <h3><?php echo $quiz["name"]; ?></h3>
                <p><?php echo $quiz["description"]; ?></p>
                <article class="menuButton">
                    <!-- Form for playing the quiz -->
                    <form action="" method="POST">
                        <input type="hidden" name="quiz_id" value="<?php echo $quiz["id"]; ?>">
                        <button type="submit" name="playQuiz" class="button jump action">Jouer !</button>
                    </form>
                    <!-- If the logged in user is the creator of the quiz, show the edit option -->
                    <?php if (isset($_SESSION["id"]) && $_SESSION["id"] == $quiz["created_by"]) : ?>
                        <form action="" method="POST">
                            <input type="hidden" name="quiz_id" value="<?php echo $quiz["id"]; ?>">
                            <button type="submit" value="Modifier" name="editQuiz" 
                            class="button modify action"><i class="fa-solid fa-pen"></i></button>
                        </form>
                    <?php endif; ?>
                </article>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php require_once(__DIR__ . "/footer.php"); ?>