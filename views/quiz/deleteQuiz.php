<?php

$quiz = new Quiz;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteQuiz"])) {
    $quiz_id = $_SESSION["quiz_id"];
    $delete = $quiz->delete($quiz_id);

    if ($delete) {
        $_SESSION["successMessage"] = "Votre quiz a bien été supprimé.";
        header("Location: ../dashboard.php");
        exit();
    }
    else {
        $_SESSION["errorMessage"] = "Une erreur est survenue lors de la suppression de votre quiz.";
    }
}

?>
<?php if (isset($_SESSION["errorMessage"])) : ?>
    <p class="message error"><?php echo $_SESSION["errorMessage"] ; ?></p>
    <?php unset($_SESSION["errorMessage"]); ?>
<?php endif; ?>

<form action="" method="POST">

    <button type="submit" class="delete" name="deleteQuiz"
    onclick="return confirm('Attention, toute suppression est définitive !')"><i 
    class="fa-solid fa-trash"></i>Supprimer mon quiz</button>

</form>