<?php

namespace Commands;

use \RuntimeException;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Util\Reddit\SubredditPageFactory;

class AddSubreddit extends AbstractCommand
{

    /**
     * @var SubredditPageFactory
     */
    protected $subredditPageFactory;

    protected function configure()
    {
        $this
            ->setName('add_subreddit')
            ->setDescription('Adds a subreddit')
            ->addArgument('subreddit', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $subreddits = $this->container['config.subreddits'];
        } catch (RuntimeException $ex) {
            $subreddits = [];
        }

        $subreddits[] = strtolower($input->getArgument('subreddit'));
        $subreddits = array_unique($subreddits);

        $file = fopen($this->container['config.subreddits_file'], 'w');
        fwrite($file, json_encode($subreddits));
        fclose($file);
    }

}
