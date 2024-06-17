<?php

use Database\Database;
use Models\Bug;

require_once 'config.php';

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

$bug = new Bug();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $comment = $_POST['comment'];
    $priority = $_POST['priority'];
    $visitorId = $_SESSION['visitor']['id'];

    $bug->createBug($title, $comment, $priority, $visitorId);

    echo json_encode(['status' => 'success', 'message' => 'Bug reported successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong.']);
}
