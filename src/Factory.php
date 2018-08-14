<?php
declare(strict_types=1);

namespace DistributedLocks;

class Factory
{
    /**
     * @var Storage
     */
    private $store;

    public function __construct(Storage $store)
    {
        $this->store = $store;
    }

    public function create(string $resource, $ttl = null): Lock
    {
        return new Lock(new Key($resource), $this->store, $ttl);
    }
}