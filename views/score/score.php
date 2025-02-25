<?php

$pageTitle = "Classement";
require_once("../header.php");
require_once(__DIR__ . "/../../classes/Autoloader.php");

$scores = new ScoreController;
$scoresList = $scores->displayScore();

?>

<main>
    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"] ; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <section class="form">
        <h2>Classement !</h2>

        <section class="formBody">
            <?php foreach($scoresList as $index => $user) : ?>
                <article class="score">
                    <p class="username"><?php echo $index + 1 . ". " . $user["username"] ; ?></p> 
                    <p class="number"><?php echo $user["score"] ; ?></p>
                </article>
            <?php endforeach ; ?>
        </section>
    </section>
</main>

<?php require_once("../footer.php"); ?>