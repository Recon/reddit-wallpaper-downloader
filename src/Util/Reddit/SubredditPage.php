<?php

namespace Util\Reddit;

use \GuzzleHttp\Client;
use \Util\Reddit\Exceptions\NoPostsException;
use \Util\Reddit\SubredditPageQueryStrategy\AbstractStrategy;

class SubredditPage
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var AbstractStrategy
     */
    protected $strategy;
    protected $lastCommentName;
    protected $subredditName;

    /**
     * @var array
     */
    protected $posts = [];

    /**
     * @param string $subredditName
     * @param string $lastCommentName
     */
    public function __construct($subredditName, $lastCommentName = '')
    {
        $this->subredditName = $subredditName;
        $this->lastCommentName = $lastCommentName;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *
     * @param AbstractStrategy $strategy
     */
    public function setStrategy(AbstractStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return string
     */
    public function getLastCommentName()
    {
        return $this->lastCommentName;
    }

    /**
     * @return string
     */
    public function getSubredditName()
    {
        return $this->subredditName;
    }

    /**
     * @throws NoPostsException
     */
    public function load()
    {
        if (is_null($this->strategy)) {
            /* @see AbstractStrategy */
            throw new \Exception("Please set a strategy before loading a page");
        }

        $response = $this->strategy->request($this);

        $data = json_decode((string) $response->getBody());

        if (!empty($data->data->children)) {
            $this->posts = $data->data->children;
        } else {
            throw new NoPostsException("No posts have been fount");
        }
    }

}
