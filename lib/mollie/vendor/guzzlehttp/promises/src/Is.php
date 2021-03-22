<?php

namespace _PhpScoperd8ff184be637\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoperd8ff184be637\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
