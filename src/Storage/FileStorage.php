<?php
declare(strict_types=1);

namespace DistributedLocks\Storage;

use DistributedLocks\Exception\LockConflictedException;
use DistributedLocks\Exception\LockNotFoundException;
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
        file_put_contents($this->directoryPath. DIRECTORY_SEPARATOR . $key . self::EXTENSION, serialize($key));
    }

    public function update(Key $key)
    {
        if($this->exists($key)) {
            file_put_contents($this->directoryPath. DIRECTORY_SEPARATOR . $key . self::EXTENSION, serialize($key));
        }

        throw new LockNotFoundException(sprintf('Lock "%s" not found.', $key));
    }

    public function delete(Key $key)
    {
        // TODO: Implement delete() method.
    }

    public function exists(Key $key): bool
    {
        return file_exists($this->directoryPath. DIRECTORY_SEPARATOR . $key . self::EXTENSION);
    }
}