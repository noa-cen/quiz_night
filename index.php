<?php

$pageTitle = "QuizNight ! - Accueil";
require_once(__DIR__ . "/views/header.php");

?>

<main>
    <section class="home">
        <article class="homeText">
            <h2>Bienvenue sur QuizNight !</h2>
            <p>Testez vos connaissances et affrontez vos amis dans un quiz fun et dynamique !</p>
            <p>Saurez-vous relever le défi ?</p> 

            <article class="homeMenu">
                <a href="./views/dashboard.php" 
                aria-label="Accéder à la page quiz" class="button jump">Prêt à jouer ?</a>
            </article>
        </article>

        <img src="./assets/img/logo.png" alt="Point d'interrogation" class="logo">
    </section>
</main>

<?php require_once(__DIR__ . "/views/footer.php"); ?>