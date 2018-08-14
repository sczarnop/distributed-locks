<?php

namespace spec\DistributedLocks\Storage;

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

    function it_sholud_add_key()
    {
        $resource = 'test_1';
        $key = new Key($resource);
        $key->setLifetime(30);
        $this->add($key);
        Assert::eq(file_exists($this->getDirectory() . DIRECTORY_SEPARATOR . $resource. '.lock'), true);
    }

    private function getDirectory()
    {
        return sys_get_temp_dir();
    }
}
