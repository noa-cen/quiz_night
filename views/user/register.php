<?php

$pageTitle = "Créer mon compte";
require_once(__DIR__ . "/../header.php");

$email = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the form input values
    $email = trim($_POST["email"]);
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = trim($_POST["password"]);
    $verifyPassword = trim($_POST["verifyPassword"]);

    // Register the user using the userController
    $result = $userController->registerUser($username, $email, $password, $verifyPassword);
}
?>

<main>
    <?php if (isset($_SESSION["errorMessage"])) : ?>
        <p class="message error"><?php echo $_SESSION["errorMessage"] ; ?></p>
        <?php unset($_SESSION["errorMessage"]); ?>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <h2>Créer mon compte !</h2>
        
        <section class="formBody">
            <article class="formItem">
                <label for="email">Adresse email:</label>
                <input type="email" id="email" name="email" placeholder="Adresse email" required
                value="<?= htmlspecialchars($email); ?>">
            </article>

            <article class="formItem">
                <label for="username">Nom d'utilisateur: </label>
                <input type="username" id="username" name="username" placeholder="Nom d'utilisateur" 
                required value="<?= htmlspecialchars($username); ?>">
            </article>

            <article class="formItem">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" 
                placeholder="8 caractères minimum dont une lettre et un chiffre" 
                required>
            </article>

            <article class="formItem">
                <label for="verifyPassword">Vérifier le mot de passe:</label>
                <input type="password" id="verifyPassword" name="verifyPassword" 
                placeholder="Vérifier le mot de passe" required>
            </article>

            <input type="submit" value="Créer mon compte" class="button jump">

            <a href="./login.php" class="formLink" aria-label="Accéder à la connexion">Se connecter
            <i class="fa-solid fa-user"></i>
        </section>
    </form>
</main>

<?php require_once(__DIR__ . "/../footer.php"); ?>