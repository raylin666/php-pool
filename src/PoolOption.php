<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Pool;

use Raylin666\Contract\PoolOptionInterface;

/**
 * Class PoolOption
 * @package Raylin666\Pool
 */
class PoolOption implements PoolOptionInterface
{
    /**
     * Min connections of pool.
     * This means the pool will create $minConnections connections when
     * pool initialization.
     *
     * @var int
     */
    private $minConnections = 1;

    /**
     * Max connections of pool.
     *
     * @var int
     */
    private $maxConnections = 10;

    /**
     * The timeout of connect the connection.
     * Default value is 10 seconds.
     *
     * @var float
     */
    private $connectTimeout = 10.0;

    /**
     * The timeout of pop a connection.
     * Default value is 3 seconds.
     *
     * @var float
     */
    private $waitTimeout = 3.0;

    /**
     * Heartbeat of connection.
     * If the value is 10, then means 10 seconds.
     * If the value is -1, then means does not need the heartbeat.
     * Default value is -1.
     *
     * @var float
     */
    private $heartbeat = -1;

    /**
     * The max idle time for connection.
     * @var float
     */
    private $maxIdleTime = 60.0;

    /**
     * PoolOption constructor.
     * @param int   $minConnections
     * @param int   $maxConnections
     * @param float $connectTimeout
     * @param float $waitTimeout
     * @param float $heartbeat
     * @param float $maxIdleTime
     */
    public function __construct(
        int $minConnections,
        int $maxConnections,
        float $connectTimeout,
        float $waitTimeout,
        float $heartbeat,
        float $maxIdleTime
    ) {
        $this->minConnections = $minConnections;
        $this->maxConnections = $maxConnections;
        $this->connectTimeout = $connectTimeout;
        $this->waitTimeout = $waitTimeout;
        $this->heartbeat = $heartbeat;
        $this->maxIdleTime = $maxIdleTime;
    }

    /**
     * @return int
     */
    public function getMaxConnections(): int
    {
        // TODO: Implement getMaxConnections() method.

        return $this->maxConnections;
    }

    /**
     * @param int $maxConnections
     * @return PoolOption
     */
    public function setMaxConnections(int $maxConnections): self
    {
        $this->maxConnections = $maxConnections;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinConnections(): int
    {
        // TODO: Implement getMinConnections() method.

        return $this->minConnections;
    }

    /**
     * @param int $minConnections
     * @return PoolOption
     */
    public function setMinConnections(int $minConnections): self
    {
        $this->minConnections = $minConnections;
        return $this;
    }

    /**
     * @return float
     */
    public function getConnectTimeout(): float
    {
        // TODO: Implement getConnectTimeout() method.

        return $this->connectTimeout;
    }

    /**
     * @param float $connectTimeout
     * @return PoolOption
     */
    public function setConnectTimeout(float $connectTimeout): self
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeartbeat(): float
    {
        // TODO: Implement getHeartbeat() method.

        return $this->heartbeat;
    }

    /**
     * @param float $heartbeat
     * @return PoolOption
     */
    public function setHeartbeat(float $heartbeat): self
    {
        $this->heartbeat = $heartbeat;
        return $this;
    }

    /**
     * @return float
     */
    public function getWaitTimeout(): float
    {
        // TODO: Implement getWaitTimeout() method.

        return $this->waitTimeout;
    }

    /**
     * @param float $waitTimeout
     * @return PoolOption
     */
    public function setWaitTimeout(float $waitTimeout): self
    {
        $this->waitTimeout = $waitTimeout;
        return $this;
    }

    /**
     * @return float
     */
    public function getMaxIdleTime(): float
    {
        // TODO: Implement getMaxIdleTime() method.

        return $this->maxIdleTime;
    }

    /**
     * @param float $maxIdleTime
     * @return PoolOption
     */
    public function setMaxIdleTime(float $maxIdleTime): self
    {
        $this->maxIdleTime = $maxIdleTime;
        return $this;
    }
}