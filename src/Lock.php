<?php
declare(strict_types=1);

namespace DistributedLocks;

use DistributedLocks\Exception\InvalidArgumentException;
use DistributedLocks\Exception\LockAcquiringException;
use DistributedLocks\Exception\LockConflictedException;
use DistributedLocks\Exception\LockReleasingException;

class Lock
{
    /**
     * @var Key
     */
    private $key;

    /**
     * @var Storage
     */
    private $store;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(Key $key, Storage $store, int $ttl = null)
    {
        $this->key = $key;
        $this->store = $store;
        $this->ttl = $ttl;
    }

    public function acquire(): bool
    {
        try {
            $this->store->add($this->key);

            if($this->ttl)
            {
                $this->refresh();
            }

        } catch (LockConflictedException $lockConflictedException) {
            return false;
        } catch (\Exception $exception) {
            throw new LockAcquiringException(sprintf('Failed to acquire the "%s" lock.', $this->key), 0, $exception);
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function refresh(int $ttl = null)
    {
        if (null === $ttl) {
            $ttl = $this->ttl;
        }
        if (!$ttl) {
            throw new InvalidArgumentException('You have to define an expiration duration.');
        }

        $this->key->setLifetime($ttl);
        $this->store->update($this->key);
    }

    public function isAcquired()
    {
        return $this->store->exists($this->key);
    }

    public function release()
    {
        $this->store->delete($this->key);

        if ($this->store->exists($this->key)) {
            throw new LockReleasingException(sprintf('Failed to release the "%s" lock.', $this->key));
        }
    }

    /**
     * @throws \Exception
     */
    public function isExpired(): bool
    {
        return $this->key->isExpired();
    }
}