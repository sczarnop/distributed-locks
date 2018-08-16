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
    private $willExpireAt;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    public function __construct(string $resource, string $owner, \DateTime $willExpireAt, \DateTimeImmutable $createdAt)
    {
        $this->resource = $resource;
        $this->owner = $owner;
        $this->willExpireAt = $willExpireAt;
        $this->createdAt = $createdAt;
    }

    public function owner(): string
    {
        return $this->owner;
    }

    public function willExpireAt(): \DateTime
    {
        return $this->willExpireAt;
    }
}
