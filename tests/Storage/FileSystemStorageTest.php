<?php

namespace ImageDownloader\Tests\Storage;

use ImageDownloader\Storage\FileSystemStorage;

class FileSystemStorageTest extends \PHPUnit_Framework_TestCase
{
    const PATH = __DIR__ . '/../Fixtures/test.storage.file';

    /**
     * @var FileSystemStorage
     */
    protected $storage;

    protected function setUp()
    {
        $this->storage = new FileSystemStorage();
    }

    public function testStoresFile()
    {
        $this->assertFileNotExists(self::PATH);

        $this->storage->store(self::PATH, 'hello');

        $this->assertFileExists(self::PATH);
        $this->assertEquals('hello', file_get_contents(self::PATH));
    }

    /**
     * @expectedException \ImageDownloader\Storage\Exception\FileOperationException
     */
    public function testFailsWhenTargetPathIsNotWritable()
    {
        $path = '/some/non-writable/path';
        $this->assertFalse(is_writable($path));

        $this->storage->store($path, 'hello');
    }

    protected function tearDown()
    {
        @unlink(self::PATH);
    }
}
