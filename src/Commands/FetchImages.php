<?php

namespace Commands;

use \GuzzleHttp\Client;
use \Model\Record;
use \Model\RecordQuery;
use \Pimple\Container;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Filesystem\Filesystem;
use \Symfony\Component\HttpFoundation\File\File;
use \Util\ExifWriter\ExifWriterFactory;

class FetchImages extends AbstractCommand
{

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ExifWriterFactory
     */
    protected $exifWriterFactory;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->client = $container['guzzle.client.factory']->create();
        $this->exifWriterFactory = $container['exif_writer.factory'];
    }

    protected function configure()
    {
        $this
            ->setName('crawl:images')
            ->setDescription('Crawled images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;

        do {
            $record = RecordQuery::create()
                ->filterByIsCrawled(false)
                ->findOne();
            if ($record) {
                $record->setIsCrawled(true);
                $record->save();
            } else {
                $this->output->writeln('<info>Done! Bye!</info>');
                return;
            }

            $this->output->writeln(sprintf("Downloading image '%s'...", substr($record->getTitle(), 0, 80)));
            $result = $this->downloadImage($record);

            $type = $result ? 'info' : 'error';
            $msg = $result ? 'OK' : 'Fail';
            $this->output->writeln(sprintf("<%s> - %s</%s>", $type, $msg, $type));

            sleep(mt_rand(1, 3));
        } while ($record);
    }

    protected function downloadImage(Record $record)
    {
        $response = $this->client->get($record->getMediaUrl());

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $directory = sprintf("%s/%s", $this->container['config.storage_path'], strtolower($record->getSubredditName()));
        $filename = sprintf("%s/%s", $directory, $record->getRedditPostName());

        $fs = new Filesystem();
        $fs->mkdir($directory);
        $fs->dumpFile($filename, $response->getBody());

        $file = new File($filename);
        $mime = $file->getMimeType();
        $ext = $file->guessExtension();

        if (strcasecmp(substr($mime, 0, 5), 'image') !== 0) {
            $fs->remove($filename);
            return false;
        }

        $file->move($directory, sprintf("%s.%s", $record->getRedditPostName(), $ext));
        $file = new File($filename . '.' . $ext);

        if ($exifWriter = $this->exifWriterFactory->create($file, $record)) {
            $exifWriter->writeMetadata();
        }

        return true;
    }

}
