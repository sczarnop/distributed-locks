<?php
declare(strict_types=1);

namespace DistributedLocks\Application;

class LockInfo
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
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $willExpireAt;

    public function __construct(string $resource, string $owner, ?\DateTime $willExpireAt, \DateTimeImmutable $createdAt)
    {
        $this->resource = $resource;
        $this->owner = $owner;
        $this->willExpireAt = $willExpireAt;
        $this->createdAt = $createdAt;
    }

    public function resource(): string
    {
        return $this->resource;
    }

    public function owner(): string
    {
        return $this->owner;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function willExpireAt(): ?\DateTime
    {
        return $this->willExpireAt;
    }
}
