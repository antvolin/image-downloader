<?php

namespace ImageDownloader\Tests\Image;

use ImageDownloader\Image\Image;
use ImageDownloader\Image\ImageFactory;
use ImageDownloader\Response;

class ImageFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesImage()
    {
        $image = ImageFactory::create('name', 'content', 'type');

        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals('name', $image->getName());
        $this->assertEquals('content', $image->getContent());
        $this->assertEquals('type', $image->getType());
    }

    /**
     * @dataProvider getImageContentTypes
     */
    public function testCreatesImageFromResponse($contentType)
    {
        $response = new Response('content', 200, ['Content-Type' => $contentType]);
        $image = ImageFactory::createFromResponse($response);

        list(, $type) = explode('/', $contentType);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertStringEndsWith($type, $image->getName());
        $this->assertEquals('content', $image->getContent());
        $this->assertEquals($type, $image->getType());
    }

    public function getImageContentTypes()
    {
        return [
            ['image/jpeg'],
            ['image/gif'],
            ['image/png'],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailsWhenNoContentTypeHeaderProvided()
    {
        ImageFactory::createFromResponse(
            new Response('content', 200, [])
        );
    }

    /**
     * @dataProvider getNonImageContentTypes
     * @expectedException \InvalidArgumentException
     */
    public function testFailsWhenNonImageContentTypeHeaderProvided($contentType)
    {
        ImageFactory::createFromResponse(
            new Response('content', 200, ['Content-Type' => $contentType])
        );
    }

    public function getNonImageContentTypes()
    {
        return [
            ['text/html'],
            ['picture/gif'],
            ['photo/jpeg'],
        ];
    }
}
