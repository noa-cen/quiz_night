<?php

// Register an autoload function to automatically include class files when needed
spl_autoload_register(function ($class) {
    // Look for the class file in the "classes" directory
    $classPath = __DIR__ . "/$class.php";
    if (file_exists($classPath)) {
        require_once $classPath;
    } else {
        // Look for it in the "models" directory
        $classPath = __DIR__ . '/../models/' . $class . '.php';
        if (file_exists($classPath)) {
            require_once $classPath;
        }
    }
});
