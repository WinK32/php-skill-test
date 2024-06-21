<?php

use Database\Database;
use Models\Bug;

require 'config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ' . APP_URL_PREFIX . '/signin.php');
    exit;
}

require_once 'Database/Database.php';
require_once 'Models/Bug.php';

$bug = new Bug();
$bugs = $bug->getAllBugs();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bug Tracker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold">Bug Tracker Dashboard</h1>
    <table class="min-w-full bg-white border border-collapse border-slate-400">
        <thead>
            <tr class="border border-slate-300">
                <th class="py-2 border border-slate-300">ID</th>
                <th class="py-2 border border-slate-300">Title</th>
                <th class="py-2 border border-slate-300">Comment</th>
                <th class="py-2 border border-slate-300">Priority</th>
                <th class="py-2 border border-slate-300">Status</th>
                <th colspan="3" class="py-2 border border-slate-300">Actions</th>
            </tr>
        </thead>
        <tbody id="bug-list" data-amount="<?= count($bugs); ?>">
        <?php foreach ($bugs as $bug): ?>
            <tr id="bug-<?= $bug['id'] ?>">
                <td class="p-2 border border-slate-300"><?= $bug['id'] ?></td>
                <td class="py-2 border border-slate-300"><?= htmlspecialchars($bug['title']) ?></td>
                <td class="py-2 border border-slate-300" data-comment><?= htmlspecialchars($bug['comment']) ?></td>
                <td class="py-2 border border-slate-300">
                    <?= Bug::textPriority($bug['priority']); ?>
                </td>
                <td class="py-2 border border-slate-300" data-status><?= ucwords(htmlspecialchars($bug['status'])); ?></td>
                <td class="py-2 border border-slate-300">
                    <select class="w-full" onchange="updateStatus(<?= $bug['id'] ?>, this.value)">
                        <option value="open" <?= $bug['status'] == 'open' ? 'selected' : '' ?>>
                            Open
                        </option>
                        <option value="in-progress" <?= $bug['status'] == 'in-progress' ? 'selected' : '' ?>>
                            In Progress
                        </option>
                        <option value="closed" <?= $bug['status'] == 'closed' ? 'selected' : '' ?>>
                            Closed
                        </option>
                    </select>
                </td>
                <td class="py-2 border border-slate-300">
                    <input type="text" class="w-full" placeholder="Comment" data-input-comment>
                </td>
                <td class="py-2 border border-slate-300">
                    <button class="border border-solid border-indigo-400 p-1 w-full bg-indigo-200 rounded-sm" onclick="addComment(<?= $bug['id'] ?>)">Add Comment</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function updateStatus(bugId, status) {
        $.post('<?= APP_URL_PREFIX ?>/update_bug.php', {id: bugId, status: status}, function (response) {
            const bug = $('#bug-' + bugId);

            bug.find('[data-status]').text(status);
        }, 'json');
    }

    function addComment(bugId) {
        const bug = $('#bug-' + bugId);
        const comment = bug.find('[data-input-comment]').val();

        $.ajax({
            type: "POST",
            url: '<?= APP_URL_PREFIX ?>/update_bug.php',
            data: {
                id: bugId,
                comment: comment
            },
            success: function (response) {
                console.log(response);

                bug.find('[data-comment]').text(comment);
                bug.find('[data-input-comment]').val('');
            }
        });
    }

    function checkForNewBugs() {
        $.ajax({
            type: "GET",
            url: '<?= APP_URL_PREFIX ?>/get_bugs.php',
            success: function (response) {
                if (response === '') {
                    return;
                }

                let bugs = JSON.parse(response);
                let bugList = $('#bug-list');

                if (bugs.length !== Number(bugList.data('amount'))) {
                    bugList.data('amount', bugs.length);

                    bugList.empty();

                    bugs.forEach(bug => {
                        bugList.append(`
                            <tr id="bug-${bug.id}">
                                <td class="p-2 border border-slate-300">${bug.id}</td>
                                <td class="py-2 border border-slate-300">${bug.title}</td>
                                <td class="py-2 border border-slate-300" data-comment>${bug.comment}</td>
                                <td class="py-2 border border-slate-300">${bug.priority}</td>
                                <td class="py-2 border border-slate-300" data-status>${bug.status}</td>
                                <td class="py-2 border border-slate-300">
                                    <select class="w-full" onchange="updateStatus(${bug.id}, this.value)">
                                        <option value="open" ${bug.status == 'open' ? 'selected' : ''}>Open</option>
                                        <option value="in-progress" ${bug.status == 'in-progress' ? 'selected' : ''}>In Progress</option>
                                        <option value="closed" ${bug.status == 'closed' ? 'selected' : ''}>Closed</option>
                                    </select>
                                </td>
                                <td class="py-2 border border-slate-300">
                                    <input type="text" class="w-full" placeholder="Comment" data-input-comment>
                                </td>
                                <td class="py-2 border border-slate-300">
                                    <button class="border border-solid border-indigo-400 p-1 w-full bg-indigo-200 rounded-sm" onclick="addComment(${bug.id})">Add Comment</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            }
        });
    }

    setInterval(checkForNewBugs, 5000); // Check for new bugs every 5 seconds
</script>
</body>
</html>
