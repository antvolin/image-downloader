<?php

namespace ImageDownloader;

class Response
{
    protected $content;
    protected $status;
    protected $headers;

    public function __construct($content, $status, array $headers = [])
    {
        $this->setStatus($status);
        $this->setContent($content);
        $this->headers = $headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function isOk()
    {
        return 200 === $this->status;
    }

    protected function setContent($content)
    {
        if (null !== $content && !is_string($content)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The Response content must be a string or object implementing __toString(), "%s" given.',
                    gettype($content)
                )
            );
        }

        $this->content = (string) $content;
    }

    protected function setStatus($status)
    {
        $status = (int) $status;

        if ($status < 100 || $this->status >= 600) {
            throw new \InvalidArgumentException(
                sprintf('The HTTP status code "%s" is not valid.', $status)
            );
        }

        $this->status = $status;
    }
}
