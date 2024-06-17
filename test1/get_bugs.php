<?php

use Database\Database;
use Models\Bug;

require 'config.php';

require 'Database/Database.php';
require 'Models/Bug.php';

$bug = new Bug();

$bugs = $bug->getAllBugs();

echo json_encode($bugs);
