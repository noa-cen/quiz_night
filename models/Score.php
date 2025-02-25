<?php

class Score extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {
        if (isset($_SESSION["id"])) {
            // Checks if a score already exists for this user
            $query = "SELECT user_id, score FROM users_score WHERE user_id = :user_id";
            $stmt = $this->getPdo()->prepare($query);
            $stmt->execute(["user_id" => $_SESSION["id"]]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            // If a user_id exists, it is added to the current score
            if ($results) {
                $query = "UPDATE users_score SET score = :score WHERE user_id = :user_id";
                $stmt = $this->getPdo()->prepare($query);
                $params = [
                    ":score" => $results["score"] + $_SESSION["score"],
                    ":user_id" => $_SESSION["id"]
                ];
                $stmt->execute($params);
            }
            // Otherwise, we create a new score
            else {
                $query = "INSERT INTO users_score(user_id, score) 
                    VALUES (:user_id, :score)";
                $stmt = $this->getPdo()->prepare($query);
                $stmt->execute([
                    ":user_id" => $_SESSION["id"], 
                    ":score" => $_SESSION["score"]
                ]);
            }
        }
    }

    public function display()
    {
        $query = "SELECT users_score.user_id, users_score.score, users.username
              FROM users_score JOIN users ON users_score.user_id = users.id
              ORDER BY users_score.score DESC";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute();
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If no scores are found, return an empty array instead of null
        return $scores ?: [];
    }
}