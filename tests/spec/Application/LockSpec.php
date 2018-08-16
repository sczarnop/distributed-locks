<?php

namespace spec\DistributedLocks\Application;

use DistributedLocks\Application\Lock;
use DistributedLocks\Domain\AccessDenied;
use DistributedLocks\Domain\Lock as LockEntity;
use DistributedLocks\Domain\LockCouldNotBeAcquired;
use DistributedLocks\Domain\LockCouldNotBeReleased;
use DistributedLocks\Domain\LockNotFound;
use DistributedLocks\Domain\LockRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockSpec extends ObjectBehavior
{
    function let(LockRepository $lockRepository)
    {
        $this->beConstructedWith($lockRepository, 'resource_1', 'owner_1', 600);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
    }

    function it_is_acquirable_where_lock_don_not_exist_yet(LockRepository $lockRepository)
    {
        $resource = 'resource_1';
        $lockRepository->get($resource)->willThrow(LockNotFound::class);
        $lockRepository->save(Argument::type(LockEntity::class))->shouldBeCalled();
        $this->acquire();
    }

    function it_is_acquirable_where_call_by_owner(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $owner = 'owner_1';
        $lockRepository->get($resource)->willReturn($lock);
        $lock->owner()->willReturn($owner);
        $lockRepository->save(Argument::type(LockEntity::class))->shouldNotBeCalled();
        $this->acquire();
    }

    function it_is_not_acquirable_when_repository_error_occurred_during_getting(LockRepository $lockRepository)
    {
        $resource = 'resource_1';
        $lockRepository->get($resource)->willThrow(\Exception::class);
        $this->shouldThrow(LockCouldNotBeAcquired::class)->during('acquire');
    }

    function it_is_not_acquirable_when_repository_error_occurred_during_saving(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $lockRepository->get($resource)->willThrow(LockNotFound::class);
        $lockRepository->save(Argument::type(LockEntity::class))->willThrow(\Exception::class);
        $this->shouldThrow(LockCouldNotBeAcquired::class)->during('acquire');
    }

    function it_is_accessible(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $owner = 'owner_1';
        $lockRepository->get($resource)->willReturn($lock);
        $lock->owner()->willReturn($owner);
        $this->hasAccess()->shouldReturn(true);
    }

    function it_is_accessible_when_lock_not_exists(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $lockRepository->get($resource)->willThrow(LockNotFound::class);
        $this->hasAccess()->shouldReturn(true);
    }

    function it_is_not_accessible(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $owner = 'owner_2';
        $lockRepository->get($resource)->willReturn($lock);
        $lock->owner()->willReturn($owner);
        $this->hasAccess()->shouldReturn(false);
    }

    function it_is_releasible(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $owner = 'owner_1';
        $lock->owner()->willReturn($owner);
        $lockRepository->remove($resource)->shouldBeCalled();
        $lockRepository->get($resource)->willReturn($lock);
        $this->release()->shouldReturn(null);
    }

    function it_is_not_releasible(LockRepository $lockRepository, LockEntity $lock)
    {
        $resource = 'resource_1';
        $owner = 'owner_2';
        $lock->owner()->willReturn($owner);
        $lockRepository->remove($resource)->shouldNotBeCalled();
        $lockRepository->get($resource)->willReturn($lock);
        $this->shouldThrow(AccessDenied::class)->during('release');
    }
}
