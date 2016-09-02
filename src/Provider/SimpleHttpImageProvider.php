<?php

namespace ImageDownloader\Provider;

use ImageDownloader\Response;

class SimpleHttpImageProvider extends HttpImageProvider
{
    const STATUS_CODE = 'Response-Status-code';
    const STATUS_TEXT = 'Response-Status-Text';

    /**
     * @var resource
     */
    private $context;

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    protected function doGet($uri)
    {
        $content = @file_get_contents(urldecode($uri), false, $this->getContext());
        if (false === $content && empty($http_response_header)) {
            throw new \RuntimeException(
                sprintf('Can not retrieve an image because uri "%s" is not reachable', $uri)
            );
        }

        $headers = $this->parseHeaders($http_response_header);

        return new Response(
            $content,
            $headers[self::STATUS_CODE],
            $headers
        );
    }

    /**
     * @return resource
     */
    private function getContext()
    {
        if (null === $this->context) {
            $options = [
                'http' => [
                    'follow_location' => true,
                    'timeout' => 1,
                    'header'  => 'Accept: ' . implode(',', $this->allowedTypes) . "\r\n"
                ],
            ];

            $this->context = stream_context_create($options);
        }

        return $this->context;
    }

    private function parseHeaders(array $rawHeaders)
    {
        $statusHeader = array_shift($rawHeaders);
        list(, $statusCode, $statusText) = explode(' ', $statusHeader, 3);

        $headers = [
            self::STATUS_CODE => $statusCode,
            self::STATUS_TEXT => $statusText,
        ];

        foreach ($rawHeaders as $header) {
            list($name, $value) = explode(':', $header);
            $headers[$name] = trim($value);
        }

        return $headers;
    }
}
