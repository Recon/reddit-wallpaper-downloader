<?php

namespace Util\ExifWriter;

use \Model\Record;
use \Symfony\Component\HttpFoundation\File\File;

abstract class AbstractExifWriter
{

    /**
     * @var Record
     */
    protected $record;

    /**
     * @var File
     */
    protected $file;

    /**
     * @param File $file
     * @param Record $record
     */
    public function __construct(File $file, Record $record)
    {
        $this->file = $file;
        $this->record = $record;
    }

    abstract public function writeMetadata();
}
