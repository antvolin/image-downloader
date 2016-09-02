<?php

namespace ImageDownloader\Provider;

use ImageDownloader\Image\ImageFactory;
use ImageDownloader\Response;

class SimpleFsImageProvider implements ImageProviderInterface
{
    protected $allowedTypes;

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    final public function get($path)
    {
        $image = ImageFactory::createFromFile($path);

        if (!in_array('image/'. $image->getType(), $this->allowedTypes, true)) {
            throw new \RuntimeException(
                sprintf('Retrieved image type "%s" is not allowed', $image->getType())
            );
        }

        return $image;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function setAllowedTypes(array $types)
    {
        $this->allowedTypes = $this->normalizeTypes($types);
    }

    /**
     * @param array $types
     *
     * @return array
     */
    private function normalizeTypes(array $types)
    {
        return array_unique(
            array_map(function ($type) {
                return 'jpg' === $type ? 'image/jpeg' : 'image/' . $type;
            }, $types)
        );
    }
}
