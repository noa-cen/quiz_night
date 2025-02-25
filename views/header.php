<?php

require_once(__DIR__ . "/../classes/Autoloader.php"); 
require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/QuizController.php");
require_once(__DIR__ . "/../controllers/ScoreController.php");

$userController = new UserController;

$session = new Session;
$session->startSession();
if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    $session->logOut();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Join QuizNight! Create your own quizzes, test your knowledge, and challenge friends in exciting trivia games.">
    <meta name="keywords" content="HTML, CSS, PHP">
    <meta name="author" content="Noa Cengarle">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/ecde10fa93.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Henny+Penny&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
    
    <link rel="stylesheet" href="/quiz_night/assets/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/quiz_night/assets/img/favicon.ico" type="image/x-icon">

    <title><?php echo $pageTitle; ?></title>
</head>
<body>
<header>
    <nav class="navbar">

        <a href="/quiz_night/index.php" aria-label="Accéder à l'accueil du site"><h1>QuizNight !</h1></a>

        <article class="nav-link">
            <ul>
                <li><a href="/quiz_night/views/dashboard.php" aria-label="Accéder aux quiz">Quiz</a></li>
                <li><a href="/quiz_night/views/score/score.php" 
                aria-label="Accéder aux scores">Classement</a></li>

                <!-- Check if the user is logged in -->
                <?php if (isset($_SESSION["username"])) : ?>
                    <li><a href="/quiz_night/views/user/editUser.php" 
                aria-label="Accéder à mon compte"><?php echo $_SESSION["username"] ?></a></li>
                <li class="login"><a href="?action=logout" 
                aria-label="Me déconnecter">Me déconnecter</a></li>
                <?php else: ?>
                    <li class="login"><a href="/quiz_night/views/user/login.php" 
                aria-label="Accéder à me connecter">Me connecter</a></li>
                <?php endif; ?>
            </ul>
        </article>

        <!-- Responsive menu -->
        <article class="responsive">
            <input type="checkbox" id="menu-hamburger">
            <label for="menu-hamburger"></label>
        </article>

    </nav>
</header>