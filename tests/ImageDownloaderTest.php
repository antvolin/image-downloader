<?php

namespace ImageDownloader\Tests;

use ImageDownloader\Image\Image;
use ImageDownloader\ImageDownloader;
use ImageDownloader\Provider\ImageProviderInterface;
use ImageDownloader\Storage\StorageInterface;

class ImageDownloaderTest extends \PHPUnit_Framework_TestCase
{
    const SRC = 'some://file.uri';
    const DST = '/some/path/to/the/file';

    /**
     * @dataProvider getImagePaths()
     */
    public function testRetrievesAndStoresImage($file)
    {
        $content = file_get_contents($file);
        $info = new \SplFileInfo($file);

        $image = new Image(
            $info->getBasename(),
            $content,
            $info->getExtension()
        );

        $provider = $this->createMock(ImageProviderInterface::class);
        $provider
            ->method('get')
            ->willReturn($image);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('store')
            ->with($this->stringStartsWith(self::DST, $content));

        $downloader = new ImageDownloader($provider, $storage);
        $result = $downloader->download(self::SRC, self::DST);

        $this->assertSame($image, $result);
    }

    public function getImagePaths()
    {
        return [
            [__DIR__ . '/Fixtures/resources/1.jpg'],
            [__DIR__ . '/Fixtures/resources/1.png'],
            [__DIR__ . '/Fixtures/resources/1.gif'],
        ];
    }

    public function testConfiguresProviderWithAllowedTypes()
    {
        $types = ['jpg', 'png'];

        $provider = $this->createMock(ImageProviderInterface::class);
        $provider
            ->expects($this->once())
            ->method('setAllowedTypes')
            ->with($types);

        $storage = $this->createMock(StorageInterface::class);

        $downloader = new ImageDownloader($provider, $storage);
        $downloader->setAllowedTypes($types);
    }
}
