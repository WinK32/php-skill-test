<?php

use Database\Database;
use Models\Bug;

require 'config.php';
if (!isset($_SESSION['visitor']['id'])) {
    $_SESSION['visitor']['id'] = rand(1000000, 10000000);
}

require 'Database/Database.php';
require 'Models/Bug.php';

$bug = new Bug();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bug Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold">Hello World</h1>

    <hr class="mb-8 mt-8">

    <button id="report-bug-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Report a Bug
    </button>
    <div id="bug-form" class="hidden">
        <form id="submit-bug-form">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="comment" placeholder="Comment" required></textarea>
            <select name="priority" required>
                <option value="3">Low</option>
                <option value="2">Medium</option>
                <option value="1">High</option>
            </select>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Submit
            </button>
        </form>
    </div>
    <div id="notification" class="hidden"></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('#report-bug-btn').click(function () {
        $('#bug-form').toggle();
    });

    $('#submit-bug-form').submit(function (event) {
        event.preventDefault();
        
        $.ajax({
            type: "POST",
            url: '<?= APP_URL_PREFIX ?>/submit_bug.php',
            data: $(this).serialize(),
            success: function (response) {
                if (response === '') {
                    return;
                }

                let msg = JSON.parse(response);

                $('#bug-form').hide();
                $('#notification').text(msg.message).removeClass('hidden');

                setTimeout(
                    function () {
                        $('#notification').text('').addClass('hidden');
                    },
                    5000
                );
            },
            error: function (msg) {
                console.log(msg)
            }
        });
    });

    function checkForUpdates() {
        $.ajax({
            type: "GET",
            url: '<?= APP_URL_PREFIX ?>/get_updates.php',
            success: function (response) {
                if (response === '[]') {
                    return;
                }

                console.log(response);

                let bugs = JSON.parse(response);

                bugs.forEach(bug => {
                    $('#notification').append(`Bug ${bug.id} (${bug.title}) is now ${bug.status} with comment: ${bug.comment}` + '<br>').removeClass('hidden');
                });
            }
        });
    }

    setInterval(checkForUpdates, 5000);
</script>
</body>
</html>