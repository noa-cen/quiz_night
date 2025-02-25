<?php

class Quiz extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // create: Creates a new quiz after ensuring the quiz name is unique
    public function create($quizData)
    {
        $query = "SELECT name FROM quizzes WHERE name = :name";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["name" => $quizData["name"]]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION["errorMessage"] = "Un quiz existe déjà avec ce nom.";
            return false;
        }

        $query = "INSERT INTO quizzes(name, image, description, created_by) 
                VALUES (:name, :image, :description, :created_by)";
        $stmt = $this->getPdo()->prepare($query);

        $success = $stmt->execute([
            ":name" => $quizData["name"], 
            ":image" => $quizData["image"], 
            ":description" => $quizData["description"], 
            ":created_by" => $_SESSION["id"]
        ]);

        if (!$success) {
            $_SESSION["errorMessage"] = "Erreur lors de la création du quiz.";
            return false;
        }

        return true;
    }

    // display: Retrieves and returns all quizzes from the database
    public function display()
    {
        $query = "SELECT * FROM quizzes";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quizzes;
    }

    // play: Retrieves and returns a specific quiz based on its ID
    public function play($quizId)
    {
        $query = "SELECT * FROM quizzes WHERE id = :id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["id" => $quizId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // edit: Updates an existing quiz's name, image, and description based on its ID
    public function edit($quiz_id, $newName, $newImage, $newDescription)
    {
        $query = "UPDATE quizzes SET name = :name, image = :image, description = :description 
        WHERE id = :id";
        $stmt = $this->getPdo()->prepare($query);
        
        $params = [
            ":name" => $newName,
            ":image" => $newImage,
            ":description" => $newDescription,
            ":id" => $quiz_id
        ];
    
        $editQuiz = $stmt->execute($params);

        if (!$editQuiz) {
            return false;
        }
        return true;
    }

    // delete: Deletes a quiz from the database based on its ID
    public function delete($quiz_id) {
        $query = "DELETE FROM quizzes WHERE id = :id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->bindParam(":id", $quiz_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        else {
            return false;
        }
    }
}