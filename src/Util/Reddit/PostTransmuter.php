<?php

namespace Util\Reddit;

use \Model\Record;
use \stdClass;

/**
 * Converts a reddit response in the form of an stdClass to a \Model\Record object
 */
class PostTransmuter
{

    /**
     * @var Record
     */
    protected $record;

    /**
     * @param stdClass $post
     */
    public function __construct(stdClass $post)
    {
        $this->generateModel($post);
    }

    /**
     * @param stdClass $post
     * @return boolean
     */
    protected function generateModel(stdClass $post)
    {
        $record = new Record();
        $record->setAuthor(strip_tags($post->data->author));
        $record->setCreatedAt((int) $post->data->created_utc);

        if (empty($post->data->preview->images[0]->source->url)) {
            return false;
        }

        $mediaUrl = urldecode($post->data->preview->images[0]->source->url);
        if (!filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
            return false;
        }
        $record->setMediaUrl($mediaUrl);
        $record->setRedditPostName($post->data->name);
        $record->setSubredditName(strtolower($post->data->subreddit));
        $record->setTitle($post->data->title);
        $record->setIsCrawled(false);

        $this->record = $record;

        return true;
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if (!$this->record) {
            return false;
        }

        if (!$this->record->validate()) {
            return false;
        }

        $this->record->save();

        return true;
    }

}
