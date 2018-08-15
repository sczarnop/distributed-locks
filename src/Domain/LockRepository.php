<?php

namespace DistributedLocks\Domain;

interface LockRepository
{
    /**
     * @throws \Exception
     */
    public function save(Lock $lock);

    /**
     * @throws LockNotFound
     */
    public function get(string $resource): Lock;

    /**
     * @throws LockNotFound
     */
    public function remove(string $resource);
}