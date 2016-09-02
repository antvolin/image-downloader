<?php

namespace ImageDownloader\Storage;

use ImageDownloader\Storage\Exception\FileOperationException;

class FileSystemStorage implements StorageInterface
{
    public function store($path, $subject)
    {
        if (@file_put_contents($path, $subject) === false) {
            throw new FileOperationException('Could not write the file ' . $path);
        }
    }
}
