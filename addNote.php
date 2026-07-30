<?php
session_start();

// Ցույց տալ PHP սխալները (Debugging-ի համար)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once("./user.php");
$user = new User();
$currentUser = $user->ReadByUser($_SESSION['email']);

if ($currentUser && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($currentUser['id']) ? $currentUser['id'] : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';

    if (!empty($title) && !empty($text) && $user_id) {
        if (method_exists($user, 'AddNote')) {
            $user->AddNote($user_id, $title, $text);
        }
    }
}

header("Location: myaccount.php");
exit();