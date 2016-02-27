<?php

use Pimple\Container;
use Acme\Console\Command\GreetCommand;
use Symfony\Component\Console\Application;

if (!file_exists($propelConfigPath = __DIR__ . '/Config/Propel/config.php')) {
    throw new RuntimeException("Missing Propel configuration file. Use '/vendor/bin/propel convert-conf' to generate it");
}
require($propelConfigPath);


$container = new Container();

$container['guzzle.client.factory'] = function(Container $container) {
    return new \Util\Guzzle\ClientFactory();
};
$container['reddit.subreddit_page.factory'] = function(Container $container) {
    return new \Util\Reddit\SubredditPageFactory($container['guzzle.client.factory']);
};

$container['exif_writer.factory'] = function(Container $container) {
    return new \Util\ExifWriter\ExifWriterFactory();
};

$container['config.storage_path'] = __DIR__ . '/../storage';
$container['config.subreddits_file'] = __DIR__ . '/../storage/subreddits.json';
$container['config.subreddits'] = $container->factory(function(Container $container) {
    if (!file_exists($path = $container['config.subreddits_file'])) {
        throw new RuntimeException("No subreddits have been added");
    }

    return json_decode(file_get_contents($path), true);
});

$application = new Application();
$application->add(new Commands\CrawlSubreddits($container));
$application->add(new Commands\AddSubreddit($container));
$application->add(new Commands\FetchImages($container));
$application->run();
