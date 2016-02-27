<?php

namespace Util\Reddit;

use \Util\ContainerAwareFactory;

class SubredditPageFactory
{

    /**
     * @var \Util\Guzzle\ClientFactory
     */
    private $clientFactory;

    public function __construct(\Util\Guzzle\ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @param string $subredditId
     * @param string $lastCommentId
     * @return \Util\Reddit\SubredditPage
     */
    public function create($subredditId, $lastCommentId = '')
    {
        $page = new SubredditPage($subredditId, $lastCommentId);
        $page->setClient($this->clientFactory->create());

        return $page;
    }

}
