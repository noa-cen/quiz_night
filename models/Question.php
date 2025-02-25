<?php

class Question extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // create: Inserts questions into the database for a given quiz and returns an array of question IDs.
    public function create($quizData, $questionsData)
    {
        $name = $quizData["name"];
        $query = "SELECT id FROM quizzes WHERE name = :name";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["name" => $name]);
        $quiz_id = $stmt->fetchColumn();

        if (!$quiz_id) {
            $_SESSION["errorMessage"] = "Quiz non trouvé.";
        }

        // Initialize an array to store the inserted question IDs.
        $questionIds = [];
        $query = "INSERT INTO questions (quiz_id, question_text) VALUES (:quiz_id, :question_text)";
        $stmt = $this->getPdo()->prepare($query);

        // Loop through each question in the provided questions data.
        foreach ($questionsData as $question) {
            if (!$stmt->execute([":quiz_id" => $quiz_id, ":question_text" => $question["question"]])) {
                $_SESSION["errorMessage"] = "Erreur lors de la création des questions.";
            }

            // Retrieve and store the ID of the last inserted question.
            $questionIds[] = $this->getPdo()->lastInsertId();
        }

        return $questionIds;
    }

    // play: Retrieves all questions associated with the current quiz based on the quiz_id stored in the session.
    public function play()
    {
        $query = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["quiz_id" => $_SESSION["quiz_id"]]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // edit: Updates the text of questions for a given quiz.
    public function edit($quiz_id, $newQuestions)
    {
        // Loop through each question in the new questions data.
        foreach ($newQuestions as $question) {
            $query = "UPDATE questions SET question_text = :question_text WHERE quiz_id = :quiz_id 
            AND id = :id";
            
            $stmt = $this->getPdo()->prepare($query);
            
            $params = [
                ":question_text" => $question["text"],
                ":quiz_id" => $quiz_id,
                ":id" => $question["id"]
            ];

            $editQuestion = $stmt->execute($params);
            
            if (!$editQuestion) {
                return false;
            }
        }
        return true;
    }
}