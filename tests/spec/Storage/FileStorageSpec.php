<?php

namespace spec\DistributedLocks\Storage;

use DistributedLocks\Exception\LockNotFoundException;
use DistributedLocks\Key;
use DistributedLocks\Storage\FileStorage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class FileStorageSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith($this->getDirectory());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FileStorage::class);
    }

    function it_should_add_key()
    {
        $resource = 'test_1';
        $key = new Key($resource);
        $this->add($key);
        Assert::eq(file_exists($this->getDirectory() . DIRECTORY_SEPARATOR . $resource. '.lock'), true);
    }

    function it_should_update_key()
    {
        $resource = 'test_1';
        $expectedKey = new Key($resource);
        file_put_contents($this->getFile($resource), serialize($expectedKey));

        $expectedKey->setLifetime(30);
        $this->update($expectedKey);

        $key = unserialize(file_get_contents($this->getFile($resource), serialize($expectedKey)));
        Assert::eq($expectedKey, $key);
    }

    function it_should_throw_lock_not_found_during_updating()
    {
        $resource = 'test_1';
        $key = new Key($resource);
        $this->shouldThrow(LockNotFoundException::class)->during('update', [$key]);
    }

    private function getFile(string $resource)
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $resource. '.lock';
    }

    private function getDirectory()
    {
        return sys_get_temp_dir();
    }
}
