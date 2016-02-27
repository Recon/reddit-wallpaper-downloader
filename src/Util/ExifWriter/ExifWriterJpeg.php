<?php

namespace Util\ExifWriter;

use \lsolesen\pel\PelEntryAscii;
use \lsolesen\pel\PelEntryCopyright;
use \lsolesen\pel\PelEntryUserComment;
use \lsolesen\pel\PelExif;
use \lsolesen\pel\PelIfd;
use \lsolesen\pel\PelJpeg;
use \lsolesen\pel\PelTag;
use \lsolesen\pel\PelTiff;

class ExifWriterJpeg extends AbstractExifWriter
{

    public function writeMetadata()
    {
        $pelJpeg = new PelJpeg($this->file->getPathname());
        $exif = new PelExif();
        $pelJpeg->setExif($exif);

        if (!$pelJpeg || !$exif) {
            return;
        }

        $tiff = new PelTiff();
        $exif->setTiff($tiff);

        $ifd0 = new PelIfd(PelIfd::IFD0);
        $ifd0->addEntry(new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $this->record->getTitle()));
        $ifd0->addEntry(new PelEntryCopyright(sprintf("https://www.reddit.com/user/", $this->record->getAuthor())));
        $tiff->setIfd($ifd0);

        $ex = new PelIfd(PelIfd::EXIF);
        $ex->addEntry(new PelEntryAscii(PelTag::FILE_SOURCE, sprintf("https://www.reddit.com/r/%s/comments/%s/", $this->record->getSubredditName(), preg_replace('/^t\d_/', '', $this->record->getRedditPostName()))));
        $ifd0->addSubIfd($ex);

        $ex = new PelIfd(PelIfd::EXIF);
        $ex->addEntry(new PelEntryUserComment(sprintf("https://www.reddit.com/r/%s/comments/%s/", $this->record->getSubredditName(), preg_replace('/^t\d_/', '', $this->record->getRedditPostName()))));
        $ifd0->addSubIfd($ex);

        $pelJpeg->saveFile($this->file->getPathname());
    }

}
