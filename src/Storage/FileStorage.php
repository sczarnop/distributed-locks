<?php
declare(strict_types=1);

namespace DistributedLocks\Storage;

use DistributedLocks\Exception\LockConflictedException;
use DistributedLocks\Key;
use DistributedLocks\Storage;

class FileStorage implements Storage
{
    const EXTENSION = '.lock';

    /**
     * @var string
     */
    private $directoryPath;

    public function __construct(string $directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }

    public function add(Key $key)
    {
        file_put_contents($this->directoryPath. DIRECTORY_SEPARATOR . $key . self::EXTENSION, $key->expiringTime()->format(\DateTime::ISO8601));
    }

    public function update(Key $key)
    {

    }

    public function delete(Key $key)
    {
        // TODO: Implement delete() method.
    }

    public function exists(Key $key)
    {
        // TODO: Implement exists() method.
    }
}