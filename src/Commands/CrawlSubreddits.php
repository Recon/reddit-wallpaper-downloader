<?php

namespace Commands;

use \Pimple\Container;
use \ReflectionClass;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Util\Reddit\Exceptions\NoPostsException;
use \Util\Reddit\PostTransmuter;
use \Util\Reddit\SubredditPage;
use \Util\Reddit\SubredditPageFactory;
use \Util\Reddit\SubredditPageQueryStrategy\AbstractStrategy;
use \Util\Reddit\SubredditPageQueryStrategy\NewPosts;
use \Util\Reddit\SubredditPageQueryStrategy\TopPosts;

class CrawlSubreddits extends AbstractCommand
{

    /**
     * @var SubredditPageFactory
     */
    protected $subredditPageFactory;

    /**
     * @var AbstractStrategy[]
     */
    protected $queryStrategies;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->subredditPageFactory = $this->container['reddit.subreddit_page.factory'];

        $this->queryStrategies = [
            new TopPosts(),
            new NewPosts()
        ];
    }

    protected function configure()
    {
        $this
            ->setName('crawl:subreddits')
            ->setDescription('Creates a list of resources from every subreddit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $subreddits = $this->container['config.subreddits'];

        foreach ($subreddits As $subreddit) {
            foreach ($this->queryStrategies as $strategy) {
                $this->crawlSubreddit($subreddit, $strategy);
            }
        }

        $this->output->writeln("\n<info>Done, arrivederci</info>\n");
    }

    /**
     * @param string $subreddit
     * @param AbstractStrategy $strategy
     */
    protected function crawlSubreddit($subreddit, AbstractStrategy $strategy)
    {

        $lastPostName = '';
        $this->output->writeln(sprintf("\nFetching items from %s using %s strategy...", $subreddit, (new ReflectionClass($strategy))->getShortName()));

        do {
            $saved = 0;

            /* @var $page SubredditPage */
            $page = $this->subredditPageFactory->create($subreddit, $lastPostName);
            $page->setStrategy($strategy);

            try {
                $page->load();
            } catch (NoPostsException $ex) {
                return;
            }

            foreach ($page->getPosts() as $post) {
                $this->output->write(sprintf("\n - Post: %s... ", $post->data->title));
                $lastPostName = $post->data->name;

                $transmuter = new PostTransmuter($post);
                if ($transmuter->save()) {
                    $this->output->write("<info>OK</info>");
                    $saved++;
                } else {
                    $this->output->write("<error>Fail</error>");
                }
            }
            sleep(mt_rand(2, 6));
        } while ($saved > 0);

        $this->output->writeln(PHP_EOL);
    }

}
