<?php

use Database\Database;
use Models\Bug;

require_once 'config.php';
if (!isset($_SESSION['visitor']['id'])) {
    $_SESSION['visitor']['id'] = rand(1000000, 10000000);
}

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

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
    <h1 class="text-2xl font-bold mt-2">Hello World</h1>

    <a class="bg-blue-200 hover:bg-blue-300 text-black-600 py-2 px-4 rounded float-right mb-4"
        href="<?= APP_URL_PREFIX ?>/dashboard.php">Engineer Dashboard</a>

    <hr class="mb-8 mt-8 clear-right">

    <button id="report-bug-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Report a Bug
    </button>
    <div id="bug-form" class="hidden">
        <form id="submit-bug-form" class="mt-2">
            <input type="text" name="title" placeholder="Title" required="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <textarea name="comment" placeholder="Comment" required="" class="p-2.5 w-500 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" style="position: relative;top: 15px;"></textarea>
            <select name="priority" required="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
