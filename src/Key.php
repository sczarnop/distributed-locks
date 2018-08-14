<?php
declare(strict_types=1);

namespace DistributedLocks;

class Key
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var \DateTime
     */
    private $expiringTime;

    /**
     * Key constructor.
     * @param string $resource
     */
    public function __construct(string $resource)
    {
        $this->resource = $resource;
    }

    public function __toString(): string
    {
        return $this->resource;
    }

    /**
     * @throws \Exception
     */
    public function setLifetime(int $ttl)
    {
        $this->expiringTime = new \DateTime();
        $this->expiringTime->add(new \DateInterval(sprintf('PT%dS', $ttl)));
    }

    /**
     * @throws \Exception
     */
    public function isExpired(): bool
    {
        return null !== $this->expiringTime && $this->expiringTime <= new \DateTimeImmutable();
    }

    public function expiringTime(): \DateTime
    {
        return $this->expiringTime;
    }
}