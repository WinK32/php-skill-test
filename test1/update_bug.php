<?php

use Models\Bug;

require 'config.php';

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

$bug = new Bug();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bugId = (int)$_POST['id'];
    $status = $_POST['status'] ?? null;
    $comment = $_POST['comment'] ?? null;
    
    $bug->updateBug($bugId, $status, $comment);

    echo json_encode(['status' => 'success']);
}
