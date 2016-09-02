<?php

namespace ImageDownloader\Storage;

interface StorageInterface
{
    /**
     * @param string $path Path to some file
     * @param string $subject The inside of a file
     *
     * Saves the file on a path
     */
    public function store($path, $subject);
}
