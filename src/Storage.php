<?php
declare(strict_types=1);

namespace DistributedLocks;

use DistributedLocks\Exception\LockConflictedException;

interface Storage
{
    /**
     * @throws LockConflictedException
     */
    public function add(Key $key);

    public function update(Key $key);

    public function delete(Key $key);

    public function exists(Key $key);
}