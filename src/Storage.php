<?php
declare(strict_types=1);

namespace DistributedLocks;

use DistributedLocks\Exception\LockConflictedException;
use DistributedLocks\Exception\LockNotFoundException;

interface Storage
{
    /**
     * @throws LockConflictedException
     */
    public function add(Key $key);

    /**
     * @throws LockNotFoundException
     */
    public function update(Key $key);

    public function delete(Key $key);

    public function exists(Key $key): bool;
}