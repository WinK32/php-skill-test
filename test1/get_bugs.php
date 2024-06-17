<?php

use Database\Database;
use Models\Bug;

require 'config.php';

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

$bug = new Bug();

$bugs = $bug->getAllBugs();

echo json_encode($bugs);
