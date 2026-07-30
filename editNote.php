<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once("./user.php");
$user = new User();
$currentUser = $user->ReadByUser($_SESSION['email']);

if ($currentUser && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $currentUser['id'];
    $note_id = isset($_POST['note_id']) ? (int)$_POST['note_id'] : 0;
    $title   = isset($_POST['title']) ? trim($_POST['title']) : '';
    $text    = isset($_POST['text']) ? trim($_POST['text']) : '';

    if ($note_id > 0 && !empty($title) && !empty($text)) {
        // Կանչում ենք user.php-ի editNote ֆունկցիան
        $user->editNote($note_id, $title, $text);
    }
}

header("Location: myaccount.php");
exit();