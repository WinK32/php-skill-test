<?php

use Database\Database;
use Models\Bug;

require_once 'config.php';

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

$bug = new Bug();

$visitorId = $_SESSION['visitor']['id'];

$updates = $bug->getBugUpdates($visitorId);

echo json_encode($updates);