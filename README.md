I am sorry, but I don't want to create a video.

But I can explain my approach here.

__TASK 1__

The basic idea was to create 2 pages.

1 public page, which is index page. And 1 private page, which is dashboard page.

For the public page it's pretty simple:

We create a form for submitting a bug, which send data via AJAX to our DataBase.

On Dashboard, we're checking for updates of our DataBase every 5 sec. And if update is found - Reload the list of bugs.

Also can be done, by adding new bugs instead of reloading the whole list, but for test purposes it's OK.

Then we create 2 AJAX-scripts for bug change (change status, and comment)
After change is applied, we set variable `notified` to 0 in our DB

On public `index` page we also listed every 5 seconds to changes in DB via AJAX, and if user-created task has been modified, it's `notified` status will be changed to `0`.
So it means, that we need to notify the user.
After the notification, we change `notified` to `1`.

We assume that user won't leave the page at any point, and he will be notified.
But even if the page reloads, he will be still notified, because of that variable wasn't changed to `1`.

The code can still be refactored to achieve DRY principle, but I didn't want to spend too much time.
If the time span is 4-5 hours, so I kept in those timelines.

About __TASK 2__

It was tricky, because string you provided didn't fully matched the convention.
And as I have never dealt with such kind of task, I should investigate and guess which string should be proper one.

`OtSrzlB7n3MjD01XlzM4MfNeam1Z+oCnO3kEkxptuS4=`

So there was `-` instead of `+` and `=` was missing

But in the end: Automaze love PHP

So... me too

__TASK 3__

It would be cool if you would provide an SQL test-sample so I can test my queries.
It would become very trivial, but I was needed to recreate your structure and populate the data to test queries properly.

But anyway the query is there.

