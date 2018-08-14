<?php

namespace spec\DistributedLocks;

use DistributedLocks\Factory;
use DistributedLocks\Lock;
use DistributedLocks\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let(Storage $store)
    {
        $this->beConstructedWith($store);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_create_lock()
    {
        $resource = 'test_resource';
        $ttl = 30;
        $this->create($resource, $ttl)->shouldReturnAnInstanceOf(Lock::class);
    }

}
