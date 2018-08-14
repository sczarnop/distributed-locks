<?php
declare(strict_types=1);

namespace DistributedLocks\Exception;

use DistributedLocks\Exception\Exception;

class LockReleasingException extends \RuntimeException implements Exception
{

}