<?php

use Database\Database;
use Models\Bug;

require 'config.php';

require 'Database/Database.php';
require 'Models/Bug.php';

$bug = new Bug();

$visitorId = $_SESSION['visitor']['id'];

$updates = $bug->getBugUpdates($visitorId);

echo json_encode($updates);