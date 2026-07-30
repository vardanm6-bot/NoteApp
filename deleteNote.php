<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once("./user.php");
$user = new User();
$currentUser = $user->ReadByUser($_SESSION['email']);

if ($currentUser && isset($_GET['id'])) {
    $user_id = $currentUser['id'];
    $note_id = (int)$_GET['id'];

    if ($note_id > 0) {
        // Կանչում ենք user.php-ի deleteNote ֆունկցիան
        $user->deleteNote($note_id, $user_id);
    }
}

header("Location: myaccount.php");
exit();