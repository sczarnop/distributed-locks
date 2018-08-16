<?php

namespace DistributedLocks\Application;

use DistributedLocks\Domain\AccessDenied;
use DistributedLocks\Domain\Lock as LockEntity;
use DistributedLocks\Domain\LockCouldNotBeAcquired;
use DistributedLocks\Domain\LockCouldNotBeReleased;
use DistributedLocks\Domain\LockNotFound;
use DistributedLocks\Domain\LockRepository;
use DistributedLocks\Domain\ResourceLocked;

class Lock
{
    /**
     * @var LockRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $resource;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(LockRepository $repository, string $resource, string $owner, int $ttl)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        $this->owner = $owner;
        $this->ttl = $ttl;
    }

    /**
     * @throws LockCouldNotBeAcquired
     * @throws ResourceLocked when is acquired by another user
     */
    public function acquire(): void
    {
        try {
            $lock = $this->repository->get($this->resource);
            if($lock->owner() != $this->owner) {
                throw new ResourceLocked(sprintf('Resource is locked by "%s"', $lock->owner()));
            }
        } catch(LockNotFound $lockNotFound) {
            try {
                $this->createLock();
            } catch(\Exception $exceptionDuringLockCreation) {
                $this->throwLockCanNotBeAcquired($exceptionDuringLockCreation);
            }
        } catch (\Exception $exception) {
            $this->throwLockCanNotBeAcquired($exception);
        }
    }

    public function hasAccess(): bool
    {
        try {
            $lock = $this->repository->get($this->resource);
            return $lock->owner() == $this->owner;
        } catch(LockNotFound $lockNotFound) {
            return true;
        }
    }

    /**
     * @throws LockNotFound
     * @throws LockCouldNotBeReleased
     * @throws AccessDenied
     */
    public function release()
    {
        try {
            if(!$this->hasAccess()) {
                throw new AccessDenied(sprintf('You do not have access to resource "%s"', $this->resource));
            }
            $this->repository->remove($this->resource);
        } catch (LockNotFound $notFound) {
            throw $notFound;
        } catch (AccessDenied $accessDenied) {
            throw $accessDenied;
        } catch(\Exception $exception) {
            throw new LockCouldNotBeReleased(
                sprintf('Lock "%s" for owner "%s" can not be released', $this->resource, $this->owner),
                0, $exception);
        }
    }

    /**
     * @throws \Exception
     */
    private function createLock()
    {
        $expiredAt = (new \DateTime())->add(new \DateInterval(sprintf('PT%dS', $this->ttl)));
        $lock = new LockEntity($this->resource, $this->owner, $expiredAt, new \DateTimeImmutable());
        $this->repository->save($lock);
    }

    /**
     * @throws LockCouldNotBeAcquired
     */
    private function throwLockCanNotBeAcquired(\Exception $prev): void
    {
        throw new LockCouldNotBeAcquired(
            sprintf('Lock "%s" for owner "%s" can not be acquired', $this->resource, $this->owner),
            0, $prev);
    }
}
