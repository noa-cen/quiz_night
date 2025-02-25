<?php

$user = new User;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteUser"])) {
    $id = $_SESSION["id"];
    $delete = $user->delete($id);

    if ($delete) {
        session_unset();
        session_destroy();
        header("Location: ../../index.php");
        exit();
    }
    else {
        $_SESSION["errorMessage"] = "Une erreur est survenue lors de la suppression de votre compte.";
    }
}
?>

<?php if (isset($_SESSION["errorMessage"])) : ?>
    <p class="message error"><?php echo $_SESSION["errorMessage"] ; ?></p>
    <?php unset($_SESSION["errorMessage"]); ?>
<?php endif; ?>

<form action="" method="POST">

    <button type="submit" class="delete" name="deleteUser"
    onclick="return confirm('Attention, toute suppression est définitive !')"><i 
    class="fa-solid fa-trash"></i>Supprimer mon compte</button>
    
</form>