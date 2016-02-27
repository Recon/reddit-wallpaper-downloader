<?php

namespace Util\Reddit\SubredditPageQueryStrategy;

use \Util\Reddit\SubredditPage;

class TopPosts extends AbstractStrategy
{

    /**
     * @param SubredditPage $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(SubredditPage $page)
    {
        return $page->getClient()->get(sprintf('https://www.reddit.com/r/%s/top.json', $page->getSubredditName()), [
            'query' => [
                'after' => $page->getLastCommentName(),
                'sort' => 'top',
                't' => 'all'
            ]
        ]);
    }

}
