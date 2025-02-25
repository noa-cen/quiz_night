<?php
require_once(__DIR__ . "/../classes/Autoloader.php"); 

class ScoreController extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addScore()
    {
        if (isset($_SESSION["id"])) {
            $scores = new Score;
            $scores->add(); 
        }
    }

    public function displayScore()
    {
        $scores = new Score;
        $scoresList = $scores->display();

        return $scoresList;
    }
}