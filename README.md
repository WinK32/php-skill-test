I am sorry, but I don't want to create a video.

But I can explain my approach here.

The basic idea was to create 2 pages.

1 public page, which is index page. And 1 private page, which is dashboard page.

For the public page it's pretty simple:

We create a form for submitting a bug, which send data via AJAX to our DataBase.

On Dashboard, we're checking for updates of our DataBase every 5 sec. And if update is found - Reload the list of bugs.

Also can be done, by adding new bugs instead of reloading, but for test purposes it's OK.

Then we create 2 AJAX-scripts for bug change (change status, and comment)
After change is applied, we set variable `notified` to 0 in our DB

On public page we also listed every 5 seconds to changes in DB, and if user-created task has been modified, it's `notified` status will be changed to `0`.
So it means, that we need to notify the user.
After the notification, we change `notified` to `1`.

We assume that user won't leave the page at any point, and he will be notified.
But even if the page reloads, he will be still notified, because of that variable wasn't changed to `1`.

