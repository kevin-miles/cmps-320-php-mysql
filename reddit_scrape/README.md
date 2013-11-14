CMPS-320-PHP-MYSQL/reddit_scrape
================================

What is this?
-------------
This is a module I used in a PHP class project. It was originally attached to a template CMS, but it is omitted here because it was not my code.


What does it do?
----------------
It grabs the current front page posts from a given subreddit using Reddit's API.

How?
----
By using the Reddit API the page is requested in .JSON format.
The JSON is then parsed for the wanted information which is hosted in a nested arrays.
Finally, the information is extracted from the nested arrays by traversing through them.

This isn't very good programming!
---------------------------------
Yes, it is bad...but it did the job. I had to finish something quickly for the project so it was pieced together as best as I could given the constraints.
Additionally, I was not familiar with any patterns or paradigms at the time.
