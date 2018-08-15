<?php

namespace spec\DistributedLocks\Domain;

use DistributedLocks\Domain\Lock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('resource_1', 'owner_1', new \DateTime(), new \DateTimeImmutable());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
    }
}
