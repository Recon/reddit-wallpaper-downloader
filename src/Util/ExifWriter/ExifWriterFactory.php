<?php

namespace Util\ExifWriter;

use \Model\Record;
use \Symfony\Component\HttpFoundation\File\File;

class ExifWriterFactory
{

    /**
     * @param File $file
     * @param Record $record
     * @return \Util\ExifWriter\ExifWriterJpeg|boolean
     */
    public function create(File $file, Record $record)
    {
        switch (strtolower($file->getExtension())) {
            case 'jpg':
            case 'jpeg':
                return new ExifWriterJpeg($file, $record);
            default:
                return false;
        }
    }

}
