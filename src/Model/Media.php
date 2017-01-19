<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Model;

use Unit6\Mandrill;
use Unit6\Mandrill\Exception;

/**
 * Mandrill Media Model Class.
 *
 * Does Mandrill support attachments?
 * http://help.mandrill.com/entries/21763806
 *
 * Tips for using images in Mandrill emails:
 * http://help.mandrill.com/entries/25252978
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Media extends Mandrill\Model
{
    const FILE_SIZE_MAX = 25000000; // 25MB

    protected $fileSize = 0;

    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    public function fromFile($path, $name = false)
    {
        if ( ! is_readable($path)) {
            throw new Exception\MediaFileNotFound($path);
        }

        $size = filesize($path);

        $max = self::FILE_SIZE_MAX;

        if ($size > $max) {
            throw new Exception\MediaFileSizeExceeded($path, $size, $max);
        }

        $this->setFileSize($size);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $this->setName($name ? $name : basename($path));
        $this->setContent(base64_encode(file_get_contents($path)));
        $this->setType(finfo_file($finfo, $path));
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($size)
    {
        $this->fileSize = $size;
    }

    public function isImage()
    {
        return (strpos($this->getType(), 'image/') === 0);
    }

    public function isValid()
    {
        return ( ! empty($this->getContent()));
    }
}