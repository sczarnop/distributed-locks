<?php

namespace DistributedLocks\Domain;

class Lock
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var \DateTime
     */
    private $expiredAt;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    public function __construct(string $resource, string $owner, \DateTime $expiredAt, \DateTimeImmutable $createdAt)
    {
        $this->resource = $resource;
        $this->owner = $owner;
        $this->expiredAt = $expiredAt;
        $this->createdAt = $createdAt;
    }

    public function owner(): string
    {
        return $this->owner;
    }
}
