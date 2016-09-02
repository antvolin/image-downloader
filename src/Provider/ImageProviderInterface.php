<?php

namespace ImageDownloader\Provider;

use ImageDownloader\Image\Image;

interface ImageProviderInterface
{
    /**
     * @param string $path Path to the resource to retrieve
     *
     * @return Image
     */
    public function get($path);

    /**
     * @param array $types
     */
    public function setAllowedTypes(array $types);
}
