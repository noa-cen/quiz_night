<?php

class User extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // register: Registers a new user in the database
    public function register($username, $email, $password)
    {
        $query = "SELECT email FROM users WHERE email = :email";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["email" => $email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION["errorMessage"] = "Un compte existe déjà avec cet email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users(username, email, password) VALUES (:username, :email, :password)";
            $stmt = $this->getPdo()->prepare($query);
            if ($stmt->execute([":username" => $username, ":email" => $email, ":password" => $hashedPassword])) {
                return true;
            } else {
                $_SESSION["errorMessage"] = "Erreur lors de l'inscription.";
            }
        }
    }

    // edit: Updates the username for an existing user
    public function edit($newEmail, $newUsername, $user_id, $oldPassword, $newPassword) 
    {
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["id" => $user_id]);
        $password = $stmt->fetch(PDO::FETCH_COLUMN);

        if (password_verify($oldPassword, $password)) {
            $query = "UPDATE users SET email = :email, username = :username, password = :password 
            WHERE id = :id";
            $stmt = $this->getPdo()->prepare($query);
            
            if (!empty($newPassword)) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $params = [
                    ":email" => $newEmail,
                    ":username" => $newUsername,
                    ":password" => $newHashedPassword,
                    ":id" => $user_id
                ];
            } else {
                $params = [
                    ":email" => $newEmail,
                    ":username" => $newUsername,
                    ":password" => $password,
                    ":id" => $user_id
                ];
            }

            $modif = $stmt->execute($params);
            return $modif;
        }
        else {
            $_SESSION["errorMessage"] = "Le mot de passe est incorrect.";
        }
    }

    // delete: Removes a user from the database by ID
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        else {
            return false;
        }
    }
}