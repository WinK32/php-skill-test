<?php

use Database\Database;
use Models\Bug;

require 'config.php';

require 'Database/Database.php';
require 'Models/Bug.php';

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
