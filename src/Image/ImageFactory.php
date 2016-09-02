<?php

namespace ImageDownloader\Image;

use ImageDownloader\Response;

class ImageFactory
{
    /**
     * @param string $name
     * @param string $content
     * @param string $type
     *
     * @return Image
     */
    public static function create($name, $content, $type)
    {
        return new Image($name, $content, $type);
    }

    public static function createFromFile($path)
    {
        if (!is_readable($path)) {
            throw new \RuntimeException(
                sprintf('Can not read image from filesystem at path "%s"', $path)
            );
        }

        if (false === $exifType = exif_imagetype($path)) {
            throw new \RuntimeException(
                sprintf('File at path "%s" is not an image', $path)
            );
        }

        $type = str_replace('image/', '', image_type_to_mime_type($exifType));

        return self::create(basename($path), file_get_contents($path), $type);

    }

    /**
     * @param Response $response
     *
     * @return Image
     *
     * @throws \InvalidArgumentException
     */
    public static function createFromResponse(Response $response)
    {
        $type = self::extractTypeFromHeaders($response->getHeaders());
        $name = uniqid('', false) . '.' . $type;
        $content = $response->getContent();

        return self::create($name, $content, $type);
    }

    /**
     * @param array $headers
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private static function extractTypeFromHeaders(array $headers)
    {
        if (!array_key_exists('Content-Type', $headers)) {
            throw new \InvalidArgumentException('No Content-Type provided');
        }

        $type = $headers['Content-Type'];
        if (0 !== strpos($type, 'image/')) {
            throw new \InvalidArgumentException(sprintf('Non-image type "%s" provided', $type));
        }

        return str_replace('image/', '', $type);
    }
}
