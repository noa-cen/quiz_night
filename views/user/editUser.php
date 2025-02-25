<?php

$pageTitle = "Modifier mon compte";
require_once(__DIR__ . "/../header.php");
require_once(__DIR__ . "/deleteUser.php");


$userController = new UserController;

// If the session has a username, retrieve the user ID, username, and email from the session
if (isset($_SESSION["username"])) {
    $user_id = $_SESSION["id"]; 
    $username = $_SESSION["username"]; 
    $email = $_SESSION["email"]; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifyUsername"])) {
    $newEmail = trim($_POST["email"]);
    $newUsername = trim($_POST["username"]);
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];
    $newVerifiedPassword = $_POST["newVerifiedPassword"];

    // Call the editUser method to update the username in the database
    $edit = $userController->editUser($newEmail, $newUsername, $user_id, $oldPassword, 
    $newPassword, $newVerifiedPassword);
}
?>

<main>
    <?php if (isset($_SESSION["errorMessage"])) : ?>
        <p class="message error"><?php echo $_SESSION["errorMessage"] ; ?></p>
        <?php unset($_SESSION["errorMessage"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["successMessage"])) : ?>
        <p class="message success"><?php echo $_SESSION["successMessage"]; ?></p>
        <?php unset($_SESSION["successMessage"]); ?>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <h2>Modifier mon compte !</h2>
        
        <section class="formBody">
            <article class="formItem">
                <label for="email">Adresse email:</label>
                <input type="email" id="email" name="email" placeholder="Adresse email" required
                value="<?= htmlspecialchars($email) ?>">
            </article>

            <article class="formItem">
                <label for="username">Nom d'utilisateur: </label>
                <input type="username" id="username" name="username" placeholder="Nom d'utilisateur" 
                value="<?= htmlspecialchars($username) ?>">
            </article>

            <article class="formItem">
                <label for="oldPassword">Ancien mot de passe:</label>
                <input type="password" id="oldPassword" name="oldPassword" 
                placeholder="Ancien mot de passe" required>
            </article>

            <article class="formItem">
                <label for="newPassword">Mot de passe: (optionnel)</label>
                <input type="password" id="newPassword" name="newPassword" 
                placeholder="8 caractères minimum dont une lettre et un chiffre">
            </article>

            <article class="formItem">
                <label for="newVerifiedPassword">Vérifier le mot de passe:</label>
                <input type="password" id="newVerifiedPassword" name="newVerifiedPassword" 
                placeholder="Vérifier le mot de passe">
            </article>

            <input type="submit" value="Modifier mon compte" name="modifyUsername" 
            class="button jump">
        </section>        
    </form>
</main>

<?php require_once(__DIR__ . "/../footer.php"); ?>