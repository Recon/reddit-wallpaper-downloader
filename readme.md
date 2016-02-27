# Reddit wallpaper downloader

Simple PHP based crawler to download images from image-based subreddits.

Key features:

- multiple strategies of fetching the images (top posts, new posts)
- maintains source and author info in the EXIF data
- dynamic list of reddits

-----


Usage:

1) Set permissions

```
chmod 0777 ./storage
```

2) Install dependencies

```
composer install
```

3) Create the sqlite database

```
vendor/bin/propel migrate
```

4) Add subreddits

```
php cli.php add_subreddit breathless
php cli.php add_subreddit nature
```

5) Crawl posts

```
php cli.php crawl:subreddits
```

6) Crawl images

```
php cli.php crawl:images
```

The images will be downloaded in the ./storage directory

------

*Optional* Add daily cron tasks for step 5 and 6