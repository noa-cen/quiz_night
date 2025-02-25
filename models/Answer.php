<?php

class Answer extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    // create: Inserts answers into the database for given questions data and question IDs
    public function create($questionsData, $questionIds)
    {
        $query = "INSERT INTO answers(question_id, answer_text, is_correct) 
                VALUES (:question_id, :answer_text, :is_correct)";
        $stmt = $this->getPdo()->prepare($query);

        // Loop through each question in the provided questions data
        foreach ($questionsData as $index => $question) {
            // Check if the question ID exists for the current question index
            if (!isset($questionIds[$index])) {
                $_SESSION["errorMessage"] = "Erreur: ID de la question introuvable.";
            }

            $questionId = $questionIds[$index];

            // Verify that the question has answers and that they are in an array format
            if (!isset($question["answers"]) || !is_array($question["answers"])) {
                $_SESSION["errorMessage"] = "Erreur: Pas de réponses pour la question.";
            }

            // Loop through each answer for the current question
            foreach ($question["answers"] as $i => $answerText) {
                // Determine if the current answer is the correct one based on the correctAnswer index
                $isCorrect = ($i + 1 == $question["correctAnswer"]) ? 1 : 0;

                $success = $stmt->execute([
                    ":question_id" => $questionId, 
                    ":answer_text" => $answerText,
                    ":is_correct" => $isCorrect
                ]);

                if (!$success) {
                    $_SESSION["errorMessage"] = "Erreur lors de l'insertion des réponses.";
                }
            }
        }

        return true;
    }

    // play: Retrieves all answers for a specific question ID
    public function play($questionId)
    {
        $query = "SELECT * FROM answers WHERE question_id = :question_id";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["question_id" => $questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // getCorrectAnswers: Retrieves the IDs of the correct answers for a specific question ID
    public function getCorrectAnswers($questionId)
    {
        $query = "SELECT id FROM answers WHERE question_id = :question_id 
        AND is_correct = 1";
        $stmt = $this->getPdo()->prepare($query);
        $stmt->execute(["question_id" => $questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // edit: Updates answers for questions based on new provided data and existing old answers
    public function edit($newQuestions, $oldAnswers)
    {
        // For each question sent from the form
        foreach ($newQuestions as $question) {
            $questionId = $question["id"];
            // Check if there are old answers for this question
            if (!isset($oldAnswers[$questionId])) {
                continue;
            }
            // For each answer of the question, update based on the order
            foreach ($question["answers"] as $index => $newAnswerText) {
                // Retrieve the old record corresponding to the current index
                if (!isset($oldAnswers[$questionId][$index])) {
                    continue;
                }
                $oldAnswer = $oldAnswers[$questionId][$index];
                // Determine if this answer should be marked as correct
                $is_correct = ($oldAnswer["id"] == $question["correct"]) ? 1 : 0;
                $query = "UPDATE answers SET answer_text = :answer_text, is_correct = :is_correct 
                        WHERE id = :answer_id";
                $stmt = $this->getPdo()->prepare($query);
                $params = [
                    ":answer_text" => $newAnswerText,
                    ":is_correct" => $is_correct,
                    ":answer_id" => $oldAnswer["id"]
                ];
                $editAnswer = $stmt->execute($params);
                if (!$editAnswer) {
                    return false;
                }
            }
        }
        return true;
    }
}
