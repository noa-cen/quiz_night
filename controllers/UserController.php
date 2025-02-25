<?php
require_once(__DIR__ . "/../classes/Autoloader.php"); 

class UserController extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // login: Validates user credentials and returns user data if valid, or false otherwise
    public function login($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            return $user;
        } 
        return false;
    }

    // registerUser: Registers a new user after validating the input data
    public function registerUser($username, $email, $password, $verifyPassword)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["errorMessage"] = "L'adresse email n'est pas écrite dans un format valide.";
        }

        // Validate the password: at least 8 characters, includes at least one letter and one number
        if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", 
        $password)) {
            $_SESSION["errorMessage"] = "Le mot de passe doit contenir au moins 8 caractères, 
            dont au moins une lettre et un chiffre.";
        }

        // Check if the password and its verification match
        if ($password !== $verifyPassword) {
            $_SESSION["errorMessage"] = "Les mots de passe ne correspondent pas.";
        }

        if (!isset($_SESSION["errorMessage"])) 
        {
            $user = new User;
            $result = $user->register($username, $email, $password);

            if ($result === true && !isset($_SESSION["errorMessage"])) {
                $_SESSION["successMessage"] = "Votre compte a été créé avec succès !";
                header("Location: login.php");
                exit();
            }
            else {
                return false;
            }
        }  
    }

    // editUser: Updates the user's username
    public function editUser($newEmail, $newUsername, $user_id, $oldPassword, $newPassword, $newVerifiedPassword) {
        if (!empty($nouveauMdp) || !empty($nouveauMdpVerifie)) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["errorMessage"] = "L'adresse email n'est pas écrite dans un format valide.";
            }

            if ($oldPassword === $newPassword) {
                $_SESSION["errorMessage"] = "Le nouveau mot de passe doit être différent de l'ancien.";
            }
            
            if (strlen($newPassword) < 8 || !preg_match("/[A-Za-z]/", $newPassword) || 
            !preg_match("/[0-9]/", $newPassword)) {
                $_SESSION["errorMessage"] = "Le mot de passe doit contenir au moins 8 caractères, dont au moins une 
                lettre et un chiffre.";
            }

            if ($newPassword !== $newVerifiedPassword) {
                $_SESSION["errorMessage"] = "Les mots de passe ne correspondent pas.";
            }
        }

        if (!isset($_SESSION["errorMessage"])) {
            $user = new User;
            $edit = $user->edit($newEmail, $newUsername, $user_id, $oldPassword, $newPassword);

            if ($edit === true) {
                $_SESSION["username"] = $newUsername;
                $_SESSION["email"] = $newEmail;
                $_SESSION["successMessage"] = "Votre compte a été modifié avec succès !";
                header("Location: editUser.php");
                exit();
            } else {
                $_SESSION["errorMessage"] = "Erreur lors de la modification du nom d'utilisateur.";
            }
        }
    }
}