<?php

namespace spec\DistributedLocks;

use DistributedLocks\Exception\LockConflictedException;
use DistributedLocks\Key;
use DistributedLocks\Lock;
use DistributedLocks\Storage;
use PhpSpec\ObjectBehavior;

class LockSpec extends ObjectBehavior
{
    function let(Key $key, Storage $store)
    {
        $this->beConstructedWith($key, $store);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
    }

    function it_should_acquirable(Key $key, Storage $store)
    {
        $store->add($key)->shouldBeCalled();
        $this->acquire()->shouldReturn(true);
    }

    function it_should_be_not_acquirable_when_lock_conflict_exception_occurred(Key $key, Storage $store)
    {
        $store->add($key)->willThrow(LockConflictedException::class);
        $this->acquire()->shouldReturn(false);
    }
}
