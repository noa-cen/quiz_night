<?php

$pageTitle = "Me connecter";
require_once(__DIR__ . "/../header.php");

$email = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim whitespace from the email and password inputs
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Log in the user using the userController's login method
    $loggedInUser = $userController->login($email, $password);

    if ($loggedInUser) {
        session_start();
        // Set session variables with user data
        $_SESSION["id"] = $loggedInUser["id"];
        $_SESSION["username"] = $loggedInUser["username"];
        $_SESSION["email"] = $loggedInUser["email"];
        $_SESSION["successMessage"] = "Bienvenue " . $_SESSION["username"] . " !";
        header("Location: ../dashboard.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<main>
    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"] ; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <?php if (!empty($error)) : ?>
        <p class="message error"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="form">

        <h2>Me connecter !</h2>
        
        <section class="formBody">
            <article class="formItem">
                <label for="email">Adresse email:</label>
                <input type="email" id="email" name="email" placeholder="Adresse email" required
                value="<?= htmlspecialchars($email); ?>">
            </article>

            <article class="formItem">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" 
                required>
            </article>

            <input type="submit" value="Me connecter" class="button jump">

            <a href="./register.php" class="formLink"
            aria-label="Accéder à la création de compte">Pas encore de compte ? 
            C'est par ici<i class="fa-solid fa-right-to-bracket fa-beat-fade"></i></i></a>
        </section>
    </form>
</main>

<?php require_once(__DIR__ . "/../footer.php"); ?>