<?php

namespace ImageDownloader;

use ImageDownloader\Storage\StorageInterface;
use ImageDownloader\Provider\ImageProviderInterface;

class ImageDownloader
{
    private $provider;
    private $storage;

    public function __construct(ImageProviderInterface $provider, StorageInterface $storage)
    {
        $this->provider = $provider;
        $this->storage = $storage;
    }

    public function download($src, $dst)
    {
        $image = $this->provider->get($src);

        $targetPath = $dst . '/' . $image->getName();
        $this->storage->store($targetPath, $image->getContent());

        return $image;
    }

    public function setAllowedTypes(array $types)
    {
        $this->provider->setAllowedTypes($types);
    }
}
