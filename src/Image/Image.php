<?php

namespace ImageDownloader\Image;

class Image
{
    protected $name;
    protected $content;
    protected $type;

    public function __construct($name, $content, $type)
    {
        if (!$name || !is_string($name)) {
            throw new \InvalidArgumentException('Image name must be non-empty string');
        }
        if (!$content || !is_string($content)) {
            throw new \InvalidArgumentException('Image content must be non-empty string');
        }
        if (!$type || !is_string($type)) {
            throw new \InvalidArgumentException('Image type must be non-empty string');
        }

        $this->name = $name;
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
