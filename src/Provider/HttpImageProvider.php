<?php

namespace ImageDownloader\Provider;

use ImageDownloader\Image\ImageFactory;
use ImageDownloader\Response;

abstract class HttpImageProvider implements ImageProviderInterface
{
    protected $allowedTypes;

    private static $imageMimeTypes = [
        'image/gif',
        'image/png',
        'image/jpeg',
    ];

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    final public function get($path)
    {
        if (!filter_var($path, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(
                sprintf('Supplied uri "%s" is invalid', $path)
            );
        }

        if (!$this->allowedTypes) {
            $this->allowedTypes = self::$imageMimeTypes;
        }

        $response = $this->doGet($path);
        if (!$response->isOk()) {
            throw new \RuntimeException(
                sprintf('Can not retrieve an image because remote side returned "%d" status code', $response->getStatus())
            );
        }

        $image = ImageFactory::createFromResponse($response);

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
        $types = $this->normalizeTypes($types);

        if ($diff = array_diff_key($types, self::$imageMimeTypes)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The type provided is not supported. Supported types are [%s]',
                    implode(', ', self::$imageMimeTypes)
                )
            );
        }

        $this->allowedTypes = $types;
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    abstract protected function doGet($uri);

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
