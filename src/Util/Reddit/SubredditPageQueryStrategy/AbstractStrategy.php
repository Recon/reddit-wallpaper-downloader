<?php

namespace Util\Reddit\SubredditPageQueryStrategy;

use \Psr\Http\Message\ResponseInterface;
use \Util\Reddit\SubredditPage;

abstract class AbstractStrategy
{

    /**
     * @return ResponseInterface
     */
    abstract public function request(SubredditPage $page);
}
